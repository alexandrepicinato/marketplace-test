<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['images', 'user'])
            ->where('active', true)
            ->where('approval_status', 'approved')
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        return view('home', compact('products'));
    }

    public function dashboard()
    {
        $products = Product::with(['images', 'user'])
            ->where('active', true)
            ->where('approval_status', 'approved')
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        return view('dashboard', compact('products'));
    }
}