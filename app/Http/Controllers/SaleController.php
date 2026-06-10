<?php

namespace App\Http\Controllers;

use App\Models\Order;

class SaleController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'product.images',
            'buyer',
            'affiliate',
            'paymentInfo',
            'shippingAddress',
        ])
            ->where('seller_user_id', auth()->id())
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        $summary = [
            'total_sales' => $orders->count(),
            'gross_revenue' => $orders->sum('subtotal'),
            'pending' => $orders->where('status', 'pending')->count(),
            'paid' => $orders->where('status', 'paid')->count(),
            'processing' => $orders->where('status', 'processing')->count(),
            'shipped' => $orders->where('status', 'shipped')->count(),
            'delivered' => $orders->where('status', 'delivered')->count(),
            'canceled' => $orders->where('status', 'canceled')->count(),
            'affiliate_commissions' => $orders->sum('commission_amount'),
            'net_revenue' => $orders->sum('subtotal') - $orders->sum('commission_amount'),
        ];

        return view('sales.index', compact('orders', 'summary'));
    }

    public function show(Order $order)
    {
        if ($order->seller_user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para ver esta venda.');
        }

        if ($order->deleted_at) {
            abort(404);
        }

        $order->load([
            'product.images',
            'buyer',
            'affiliate',
            'paymentInfo',
            'shippingAddress',
            'trackingEvents.admin',
        ]);

        return view('sales.show', compact('order'));
    }
}