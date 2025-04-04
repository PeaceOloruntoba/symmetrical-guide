<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Credit;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display the company dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $company = Auth::user()->company;

        // Get statistics
        $totalProducts = $company->products()->count();

        // Get orders that contain products from this company
        $companyOrders = Order::whereHas('items.product', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        });

        $totalOrders = $companyOrders->count();

        // Calculate total sales from order items for this company's products
        $totalSales = OrderItem::whereHas('product', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->whereHas('order', function ($query) {
            $query->where('status', 'delivered');
        })->sum(\DB::raw('price * quantity'));

        // Get company credit balance - assuming there's a credit_balance column in the companies table
        $totalCredits = $company->credit_balance ?? 0;

        // Get recent products
        $recentProducts = $company->products()->latest()->take(5)->get();

        // Get popular products (most ordered)
        $popularProducts = $company->products()
            ->withCount([
                'orderItems as orders_count' => function ($query) {
                    $query->select(\DB::raw('count(distinct order_id)'));
                }
            ])
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();

        // Get recent orders
        $recentOrders = Order::whereHas('items.product', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->with(['user', 'items.product'])->latest()->take(5)->get();

        return view('company.dashboard', compact(
            'company',
            'totalProducts',
            'totalOrders',
            'totalSales',
            'totalCredits',
            'recentProducts',
            'popularProducts',
            'recentOrders'
        ));
    }

    /**
     * Display the company profile.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $company = Auth::user()->company;
        return view('company.profile', compact('company'));
    }

    /**
     * Update the company profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        // Validate user data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        // Validate company data
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'url'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Handle logo upload
        $logoPath = $company->logo;
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($logoPath && Storage::exists($logoPath)) {
                Storage::delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('company-logos', 'public');
        }

        // Update company data
        $company->update([
            'company_name' => $request->company_name,
            'description' => $request->description,
            'website' => $request->website,
            'phone' => $request->phone,
            'address' => $request->address,
            'logo' => $logoPath,
        ]);

        return redirect()->route('company.profile')->with('success', 'Profile updated successfully');
    }

    /**
     * Display the company's products.
     *
     * @return \Illuminate\View\View
     */
    public function products()
    {
        $company = Auth::user()->company;
        $products = $company->products()->paginate(12);

        return view('company.products.index', compact('products', 'company'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function createProduct()
    {
        $company = Auth::user()->company;
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('company.products.create', compact('company', 'categories'));
    }

    /**
     * Display the company's orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function orders(Request $request)
    {
        $company = Auth::user()->company;

        $ordersQuery = Order::whereHas('items.product', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->with(['user', 'items.product']);

        // Apply filters
        if ($request->filled('status')) {
            $ordersQuery->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $ordersQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $ordersQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $ordersQuery->latest()->paginate(15);

        return view('company.orders.index', compact('company', 'orders'));
    }

    /**
     * Display the company's credits.
     *
     * @return \Illuminate\View\View
     */
    public function credits()
    {
        $user = Auth::user();
        $company = $user->company;
        $credits = Credit::where('user_id', $user->id)->latest()->paginate(10);
        $totalCredits = Credit::where('user_id', $user->id)
            ->where('type', 'purchase')
            ->sum('amount') -
            Credit::where('user_id', $user->id)
                ->where('type', 'usage')
                ->sum('amount');

        return view('company.credits.index', compact('credits', 'company', 'totalCredits'));
    }

    /**
     * Show the form for purchasing credits.
     *
     * @return \Illuminate\View\View
     */
    public function purchaseCredits()
    {
        $company = Auth::user()->company;
        return view('company.credits.purchase', compact('company'));
    }
}