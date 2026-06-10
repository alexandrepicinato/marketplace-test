<?php

namespace App\Http\Controllers;

use App\Models\Order;

class AffiliateEarningController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'product.images',
            'buyer',
            'seller',
        ])
            ->where('affiliate_user_id', auth()->id())
            ->where('commission_amount', '>', 0)
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        $summary = [
            'pending' => $orders->where('commission_status', 'pending')->sum('commission_amount'),
            'approved' => $orders->where('commission_status', 'approved')->sum('commission_amount'),
            'rejected' => $orders->where('commission_status', 'rejected')->sum('commission_amount'),
            'paid' => $orders->where('commission_status', 'paid')->sum('commission_amount'),
            'total' => $orders->sum('commission_amount'),
        ];

        return view('affiliate-earnings.index', compact('orders', 'summary'));
    }
}