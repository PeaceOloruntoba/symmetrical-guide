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
     * Create a checkout session for Stripe.
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
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($plan->currency),
                            'product_data' => [
                                'name' => $plan->name,
                                'description' => 'Subscription to ' . $plan->name,
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
                    'ends_at' => now()->addMonth(), // Assuming monthly billing
                    'status' => 'active',
                ]);

                return redirect()->route('company.subscription.index')
                    ->with('success', 'Subscription successful! Your ' . $plan->name . ' is now active.');
            } else {
                return redirect()->route('company.subscription.create')
                    ->with('error', 'Payment was not completed. Please try again.');
            }
        } catch (ApiErrorException $e) {
            return redirect()->route('company.subscription.create')
                ->with('error', $e->getMessage());
        }
    }
}