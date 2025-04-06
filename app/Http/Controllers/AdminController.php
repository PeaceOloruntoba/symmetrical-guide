<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
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
}
