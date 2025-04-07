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
        $subcategories = Category::where('parent_id', $category->id)->get();
        // If you also want to display products directly under the main category (level 0)
        $products = Product::where('category_id', $category->id)->paginate(12);

        return view('subcategories.index', compact('subcategories', 'category', 'products'));
    }

    /**
     * Display the products for a specific subcategory.
     */
    public function showSubcategory(Category $subcategory): View
    {
        $products = Product::where('category_id', $subcategory->id)->paginate(12);
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

        $categories = Category::where('name', 'like', "%{$query}%")->get();
        $subcategories = Subcategory::where('name', 'like', "%{$query}%")->get();

        return view('products.index', compact('products', 'query', 'categories', 'subcategories'));
    }
}
