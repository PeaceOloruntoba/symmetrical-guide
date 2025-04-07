<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CreditController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// // Public routes
// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');


Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/categories', [UserController::class, 'categoriesIndex'])->name('categories.index');
Route::get('/categories/{category}', [UserController::class, 'showCategory'])->name('categories.show');
Route::get('/subcategories/{subcategory}', [UserController::class, 'showSubcategory'])->name('subcategories.show'); // We'll need this later
Route::get('/products/{product}', [UserController::class, 'showProduct'])->name('products.show'); // For product details
Route::get('/search', [UserController::class, 'searchProducts'])->name('search.products');

// Auth routes group
Route::group(['namespace' => 'Auth'], function () {
    // Guest routes (only accessible when not logged in)
    Route::middleware('guest')->group(function () {
        // Login routes - limit to 5 attempts per minute
        Route::middleware('throttle:5,1')->group(function () {
            Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
            Route::post('/login', [AuthController::class, 'login']);
        });

        // Registration routes - limit to 3 attempts per minute
        Route::middleware('throttle:3,1')->group(function () {
            Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
            Route::post('/register', [AuthController::class, 'register']);
            Route::get('/register/company', [AuthController::class, 'showCompanyRegistrationForm'])->name('register.company');
            Route::post('/register/company', [AuthController::class, 'registerCompany']);
        });

        // Password reset routes - limit to 3 attempts per minute
        Route::middleware('throttle:3,1')->group(function () {
            Route::get('/password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
            Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
            Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
            Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');
        });
    });

    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // User routes
    Route::prefix('user')->middleware(['role:user'])->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    });

    // Company routes
    Route::middleware(['auth', 'role:company'])->prefix('company')->name('company.')->group(function () {
        Route::get('/dashboard', [CompanyController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CompanyController::class, 'profile'])->name('profile');
        Route::put('/profile', [CompanyController::class, 'updateProfile'])->name('profile.update');

        // Products
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');

        // Product routes with company ownership check middleware
        Route::middleware(['check.product.owner'])->group(function () {
            Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
            Route::post('/products/images/{image}/order', [ProductController::class, 'updateImageOrder'])->name('products.images.order');
        });

        // Orders
        Route::get('/orders', [CompanyController::class, 'orders'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

        // Credits
        Route::get('/credits', [CompanyController::class, 'credits'])->name('credits.index');
        Route::get('/credits/purchase', [CompanyController::class, 'purchaseCredits'])->name('credits.purchase');
        Route::post('/credits/purchase', [CreditController::class, 'purchase'])->name('credits.process');
    });

    // Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/companies', [AdminController::class, 'companies'])->name('admin.companies.index');
    Route::get('/companies/{company}', [AdminController::class, 'showCompany'])->name('admin.companies.show');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
});

});
