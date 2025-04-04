<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Credit;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $products = $company->products()->latest()->take(8)->get();
        $recentOrders = $company->orders()->latest()->take(5)->get();

        return view('company.dashboard', compact('company', 'products', 'recentOrders'));
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
     * @return \Illuminate\View\View
     */
    public function orders()
    {
        $company = Auth::user()->company;
        $orders = $company->orders()->with('user')->paginate(10);

        return view('company.orders.index', compact('orders', 'company'));
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