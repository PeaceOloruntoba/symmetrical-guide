<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Display a listing of companies.
     */
    public function companies(): View
    {
        $companies = Company::paginate(10); // Adjust pagination as needed
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Display the details of a specific company.
     *
     * @param  Company  $company
     */
    public function showCompany(Company $company): View
    {
        $products = $company->products()->paginate(10); // Adjust pagination as needed
        return view('admin.companies.show', compact('company', 'products'));
    }

    /**
     * Display a listing of users.
     */
    public function users(): View
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'company');
        })->paginate(10); // Adjust pagination and exclude companies
        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the details of a specific user.
     *
     * @param  User  $user
     */
    public function showUser(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Display a listing of plans.
     */
    public function plans(): View
    {
        $plans = Plan::orderBy('price')->paginate(10);
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function createPlan(): View
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created plan in storage.
     */
    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'billing_period' => 'required|in:month,year',
            'has_chat' => 'boolean',
            'has_company_list' => 'boolean',
            'has_product_page' => 'boolean',
            'has_wallet_system' => 'boolean',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ]);

        // Set boolean fields to false if not present in request
        $booleanFields = ['has_chat', 'has_company_list', 'has_product_page', 'has_wallet_system', 'is_active', 'is_popular'];
        foreach ($booleanFields as $field) {
            if (!isset($validated[$field])) {
                $validated[$field] = false;
            }
        }

        Plan::create($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function editPlan(Plan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function updatePlan(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'billing_period' => 'required|in:month,year',
            'has_chat' => 'boolean',
            'has_company_list' => 'boolean',
            'has_product_page' => 'boolean',
            'has_wallet_system' => 'boolean',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ]);

        // Set boolean fields to false if not present in request
        $booleanFields = ['has_chat', 'has_company_list', 'has_product_page', 'has_wallet_system', 'is_active', 'is_popular'];
        foreach ($booleanFields as $field) {
            if (!isset($validated[$field])) {
                $validated[$field] = false;
            }
        }

        $plan->update($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroyPlan(Plan $plan)
    {
        // Check if plan has active subscriptions
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}
