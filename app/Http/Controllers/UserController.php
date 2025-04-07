<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the homepage with categories.
     */
    public function index(): View
    {
        $categories = Category::where('parent_id', null)->get(); // Only main categories
        return view('welcome', compact('categories'));
    }

    /**
     * Display a listing of all main categories.
     */
    public function categoriesIndex(): View
    {
        $categories = Category::where('parent_id', null)->get(); // Only main categories
        return view('categories.index', compact('categories'));
    }

    /**
     * Display subcategories and some products for a specific category.
     */
    public function showCategory(Category $category): View
    {
        $subcategories = Category::where('parent_id', $category->id)->with('products')->get();
        $categories = Category::where('parent_id', null)->get(); // Fetch main categories for the navbar
        return view('categories.show', compact('category', 'subcategories', 'categories'));
    }

    /**
     * Display all products for a specific subcategory.
     */
    public function showSubcategory(Category $subcategory): View
    {
        $products = Product::where('category_id', $subcategory->id)->paginate(12);
        $categories = Category::where('parent_id', null)->get(); // Fetch main categories for the navbar
        return view('products.index', compact('products', 'subcategory', 'categories'));
    }

    /**
     * Display the details for a specific product.
     */
    public function showProduct(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    /**
     * Display search results for products, including those under matching subcategories.
     */
public function searchProducts(Request $request): View
    {
        $query = $request->input('search');

        $products = Product::where('name', 'like', "%{$query}%")
                            ->orWhere('description', 'like', "%{$query}%")
                            ->orWhereHas('category', function ($q) use ($query) {
                                $q->where('name', 'like', "%{$query}%");
                            })
                            ->paginate(12)
                            ->withQueryString();

        $categories = Category::where('parent_id', null)->get();

        return view('products.index', compact('products', 'query', 'categories'));
    }
}
