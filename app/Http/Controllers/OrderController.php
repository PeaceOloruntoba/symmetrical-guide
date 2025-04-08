<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $company = Auth::user()->company;

        // Get orders that contain products from this company
        $orders = Order::whereHas('items.product', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->latest()->paginate(10);

        return view('company.orders.index', compact('company', 'orders'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $company = Auth::user()->company;

        // Check if this order contains products from this company
        $hasCompanyProducts = $order->items()->whereHas('product', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->exists();

        if (!$hasCompanyProducts) {
            abort(403, 'Unauthorized action.');
        }

        // Get only the order items that belong to this company
        $orderItems = $order->items()->whereHas('product', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->get();

        // Calculate subtotal for this company's items
        $subtotal = $orderItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('company.orders.show', compact('company', 'order', 'orderItems', 'subtotal'));
    }

    /**
     * Update the order status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $company = Auth::user()->company;

        // Check if this order contains products from this company
        $hasCompanyProducts = $order->items()->whereHas('product', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->exists();

        if (!$hasCompanyProducts) {
            abort(403, 'Unauthorized action.');
        }

        $order->status = $request->status;
        $order->save();

        return redirect()->route('company.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}