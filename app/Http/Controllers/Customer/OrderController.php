<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class OrderController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'order_type' => 'required|in:pickup,delivery',
            'items' => 'required|array',
        ]);
    
        $lineItems = [];
        $total = 0;
    
        foreach ($request->items as $menu_id => $qty) {
            if ($qty > 0) {
                $menu = Menu::findOrFail($menu_id);
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'myr',
                        'unit_amount' => intval($menu->price * 100), // in sen
                        'product_data' => [
                            'name' => $menu->name,
                            'description' => $menu->description,
                        ],
                    ],
                    'quantity' => $qty,
                ];
                $total += $menu->price * $qty;
            }
        }
    
        if (count($lineItems) === 0) {
            return back()->with('error', 'Please select at least one item.');
        }
    
        // Store session info in Laravel session for later use
        session([
            'order_data' => [
                'restaurant_id' => $request->restaurant_id,
                'order_type' => $request->order_type,
                'items' => $request->items,
                'total' => $total,
            ]
        ]);
    
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('customer.payment.success'),
            'cancel_url' => route('customer.restaurants'),
        ]);
    
        return redirect($checkoutSession->url);
    }

    public function paymentSuccess()
    {
        $data = session('order_data');

        if (!$data) {
            return redirect()->route('customer.restaurants')->with('error', 'Payment session expired.');
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $data['restaurant_id'],
            'order_type' => $data['order_type'],
            'total_price' => $data['total'],
            'points_earned' => floor($data['total']),
        ]);

        foreach ($data['items'] as $menu_id => $qty) {
            if ($qty > 0) {
                $menu = Menu::find($menu_id);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu_id,
                    'quantity' => $qty,
                    'unit_price' => $menu->price,
                ]);
            }
        }

        auth()->user()->increment('points', floor($data['total']));
        session()->forget('order_data');

        return redirect()->route('customer.order.success')->with('points', floor($data['total']));
    }

}
