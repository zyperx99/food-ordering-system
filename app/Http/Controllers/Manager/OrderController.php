<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    //
    public function index()
    {
        $orders = Order::with('items.menu', 'user')
            ->whereHas('restaurant', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $todaySales = $orders->where('status', 'accepted')
            ->where('created_at', '>=', now()->startOfDay())
            ->sum('total_price');

        $totalSales = $orders->where('status', 'accepted')->sum('total_price');

        return view('manager.orders.index', compact('orders', 'todaySales', 'totalSales'));
    }

    public function accept(Order $order)
    {
        $this->authorizeManager($order);
        $order->update(['status' => 'accepted']);
        return back()->with('success', 'Order accepted.');
    }

    public function reject(Order $order)
    {
        $this->authorizeManager($order);
        $order->update(['status' => 'rejected']);
        return back()->with('error', 'Order rejected.');
    }

    private function authorizeManager(Order $order)
    {
        if ($order->restaurant->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
}
