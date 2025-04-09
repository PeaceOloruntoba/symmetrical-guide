<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        $activeSubscription = $user->activeSubscription();

        return view('company.subscription.index', compact('company', 'activeSubscription'));
    }

    /**
     * Show the form for purchasing a subscription.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        $company = $user->company;
        $activeSubscription = $user->activeSubscription();

        // Fetch available plans from the database
        $plans = Plan::where('is_active', true)
            ->orderBy('price')
            ->get();

        return view('company.subscription.create', compact('company', 'plans', 'activeSubscription'));
    }

    /**
     * Create a checkout session for Stripe with Alipay.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::create([
                'payment_method_types' => ['alipay', 'card'], // Include both Alipay and card
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($plan->currency),
                            'product_data' => [
                                'name' => $plan->name,
                                'description' => '订阅 ' . $plan->name,
                            ],
                            'unit_amount' => $plan->price * 100, // Amount in cents
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('company.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}&plan_id=' . $plan->id,
                'cancel_url' => route('company.subscription.create'),
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                ],
                'locale' => 'zh',
            ]);

            return redirect($session->url);
        } catch (ApiErrorException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle successful checkout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Verify the session
            $session = Session::retrieve($request->session_id);

            if ($session->payment_status === 'paid') {
                // Cancel any existing active subscriptions
                $user->subscriptions()
                    ->where('status', 'active')
                    ->update(['status' => 'canceled', 'ends_at' => now()]);

                // Create subscription
                $subscription = Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'starts_at' => now(),
                    'ends_at' => $plan->billing_period === 'month' ? now()->addMonth() : now()->addYear(),
                    'status' => 'active',
                    'transaction_id' => $session->payment_intent,
                ]);

                // Generate and send invoice
                $this->generateAndSendInvoice($user, $plan, $subscription);

                return redirect()->route('company.subscription.index')
                    ->with('success', '订阅成功！您的' . $plan->name . '计划现已激活。');
            } else {
                return redirect()->route('company.subscription.create')
                    ->with('error', '支付未完成。请重试。');
            }
        } catch (ApiErrorException $e) {
            return redirect()->route('company.subscription.create')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Generate and send invoice for subscription
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Plan  $plan
     * @param  \App\Models\Subscription  $subscription
     * @return void
     */
    private function generateAndSendInvoice($user, $plan, $subscription)
    {
        // Generate invoice number (IN-XXXX format)
        $invoiceCount = Subscription::count();
        $invoiceNumber = 'IN-' . str_pad($invoiceCount, 4, '0', STR_PAD_LEFT);

        // Get company details
        $company = $user->company;

        // Create PDF using a package like barryvdh/laravel-dompdf
        $pdf = \PDF::loadView('invoices.subscription', [
            'user' => $user,
            'company' => $company,
            'plan' => $plan,
            'subscription' => $subscription,
            'invoiceNumber' => $invoiceNumber,
            'invoiceDate' => now()->format('d.m.Y'),
        ]);

        $filename = 'invoice-' . $invoiceNumber . '.pdf';
        $pdfPath = storage_path('app/invoices/' . $filename);

        // Ensure the directory exists before saving
        $directory = storage_path('app/invoices');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Save PDF to storage
        $pdf->save($pdfPath);

        // Send email with PDF attachment to user
        \Mail::send('emails.invoice', [
            'user' => $user,
            'plan' => $plan,
            'invoiceNumber' => $invoiceNumber
        ], function ($message) use ($user, $pdfPath, $invoiceNumber) {
            $message->to($user->email)
                ->subject('您的发票 #' . $invoiceNumber)
                ->attach($pdfPath);
        });

        // Send a separate email with PDF attachment to invoice@germanware.de
        \Mail::send('emails.invoice', [
            'user' => $user,
            'plan' => $plan,
            'invoiceNumber' => $invoiceNumber
        ], function ($message) use ($pdfPath, $invoiceNumber) {
            $message->to('invoice@germanware.de')
                ->subject('您的发票 #' . $invoiceNumber)
                ->attach($pdfPath);
        });
    }
}