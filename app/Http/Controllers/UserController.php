<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the homepage with categories.
     */
    public function index(): View
    {
        $categories = Category::all();
        return view('welcome', compact('categories'));
    }

    /**
     * Display a listing of all categories.
     */
    public function categoriesIndex(): View
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Display the subcategories for a specific category.
     */
    public function showCategory(Category $category): View
    {
        $subcategories = $category->subcategories;
        return view('products.index', compact('subcategories', 'category'));
    }

    /**
     * Display the products for a specific subcategory.
     */
    public function showSubcategory(Subcategory $subcategory): View
    {
        $products = $subcategory->products()->paginate(12); // Adjust pagination as needed
        return view('products.index', compact('products', 'subcategory'));
    }

    /**
     * Display the details for a specific product.
     */
    public function showProduct(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    /**
     * Display search results for products.
     */
    public function searchProducts(Request $request): View
    {
        $query = $request->input('search');
        $products = Product::where('name', 'like', "%{$query}%")
                            ->orWhere('description', 'like', "%{$query}%")
                            ->paginate(12)
                            ->withQueryString();

        return view('products.index', compact('products', 'query'));
    }
}
