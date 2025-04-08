<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductCertificate;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $company = Auth::user()->company;
        $products = $company->products()->latest()->paginate(12);

        return view('company.products.index', compact('products', 'company'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $company = Auth::user()->company;
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('company.products.create', compact('company', 'categories'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'colors' => 'nullable|array',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'certificate' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $company = Auth::user()->company;

        // Create product
        $product = new Product([
            'company_id' => $company->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => true,
        ]);

        $product->save();

        // Attach category
        $product->categories()->attach($request->category_id);

        // Handle colors
        if ($request->has('colors') && is_array($request->colors)) {
            // Store colors in the product_colors table
            foreach ($request->colors as $color) {
                ProductColor::create([
                    'product_id' => $product->id,
                    'color' => $color,
                ]);
            }
        }

        // Handle product images
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $path = $image->store('product-images', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'order' => 0, // Default order
                ]);
            }
        }

        // Handle certificate
        if ($request->hasFile('certificate')) {
            $certificatePath = $request->file('certificate')->store('product-certificates', 'public');

            ProductCertificate::create([
                'product_id' => $product->id,
                'certificate_path' => $certificatePath,
                'name' => $request->file('certificate')->getClientOriginalName(),
            ]);
        }

        return redirect()->route('company.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        return view('company.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        // Explicitly load the colors relationship
        $product->load(['colors', 'images', 'certificates', 'categories']);

        // Debug: Check if colors are loaded
        \Log::info('Product colors for product ID ' . $product->id . ':', [
            'colors_count' => $product->colors ? $product->colors->count() : 0,
            'colors_data' => $product->colors
        ]);

        $company = Auth::user()->company;
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('company.products.edit', compact('product', 'categories', 'company'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'colors' => 'nullable|array',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'certificate' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:product_images,id',
            'is_active' => 'nullable|boolean',
        ]);

        // Log the colors being submitted for debugging
        \Log::info('Colors submitted for product ID ' . $product->id . ':', [
            'colors' => $request->colors ?? []
        ]);

        // Update product basic info
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->has('is_active'),
        ]);

        // Update category
        $product->categories()->sync([$request->category_id]);

        // Handle colors
        // First, delete all existing colors for this product
        ProductColor::where('product_id', $product->id)->delete();

        // Then add the new colors if any were submitted
        if ($request->has('colors') && is_array($request->colors)) {
            foreach ($request->colors as $color) {
                if (!empty($color)) {
                    ProductColor::create([
                        'product_id' => $product->id,
                        'color' => $color,
                    ]);
                }
            }
        }

        // Delete images if requested
        if ($request->has('delete_images')) {
            $imagesToDelete = ProductImage::whereIn('id', $request->delete_images)
                ->where('product_id', $product->id)
                ->get();

            foreach ($imagesToDelete as $image) {
                if (Storage::exists('public/' . $image->image_path)) {
                    Storage::delete('public/' . $image->image_path);
                }
                $image->delete();
            }
        }

        // Add new images
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $path = $image->store('product-images', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'order' => 0, // Default order
                ]);
            }
        }

        // Handle certificate
        if ($request->hasFile('certificate')) {
            // Delete old certificate if exists
            $oldCertificate = $product->certificates()->first();
            if ($oldCertificate) {
                if (Storage::exists('public/' . $oldCertificate->certificate_path)) {
                    Storage::delete('public/' . $oldCertificate->certificate_path);
                }
                $oldCertificate->delete();
            }

            // Upload new certificate
            $certificatePath = $request->file('certificate')->store('product-certificates', 'public');

            ProductCertificate::create([
                'product_id' => $product->id,
                'certificate_path' => $certificatePath,
                'name' => $request->file('certificate')->getClientOriginalName(),
            ]);
        }

        return redirect()->route('company.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        // Delete product images
        foreach ($product->images as $image) {
            if (Storage::exists('public/' . $image->image_path)) {
                Storage::delete('public/' . $image->image_path);
            }
        }

        // Delete certificates
        foreach ($product->certificates as $certificate) {
            if (Storage::exists('public/' . $certificate->certificate_path)) {
                Storage::delete('public/' . $certificate->certificate_path);
            }
        }

        // Delete the product (this will cascade delete related records)
        $product->delete();

        return redirect()->route('company.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Set a product image order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductImage  $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateImageOrder(Request $request, ProductImage $image)
    {
        $request->validate([
            'order' => 'required|integer|min:0',
        ]);

        $image->update(['order' => $request->order]);

        return back()->with('success', 'Image order updated successfully.');
    }
}