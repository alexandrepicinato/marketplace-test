<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PurchaseController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'product.images',
            'seller',
            'affiliate',
            'paymentInfo',
            'shippingAddress',
        ])
            ->where('buyer_user_id', auth()->id())
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        return view('purchases.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->buyer_user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para ver esta compra.');
        }

        if ($order->deleted_at) {
            abort(404);
        }

        $order->load([
            'product.images',
            'seller',
            'affiliate',
            'paymentInfo',
            'shippingAddress',
            'trackingEvents.admin',
        ]);

        return view('purchases.show', compact('order'));
    }
}