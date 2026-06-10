<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAffiliate;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    public function index()
    {
        $affiliatedProductIds = ProductAffiliate::where('affiliate_user_id', auth()->id())
            ->where('status', 'active')
            ->pluck('product_id')
            ->toArray();

        $products = Product::with(['images', 'user'])
            ->where('active', true)
            ->where('approval_status', 'approved')
            ->whereNull('deleted_at')
            ->where('accepts_affiliation', true)
            ->where('user_id', '!=', auth()->id())
            ->latest()
            ->get();

        return view('affiliates.index', compact('products', 'affiliatedProductIds'));
    }

    public function store(Product $product)
    {
        if (!$product->active || !$product->accepts_affiliation) {
            abort(403, 'Este produto não está disponível para afiliação.');
        }

        if ($product->user_id === auth()->id()) {
            abort(403, 'Você não pode se afiliar ao próprio produto.');
        }

        $affiliation = ProductAffiliate::where('product_id', $product->id)
            ->where('affiliate_user_id', auth()->id())
            ->first();

        if ($affiliation) {
            $affiliation->update([
                'status' => 'active',
                'affiliate_code' => $affiliation->affiliate_code ?: $this->generateAffiliateCode(),
            ]);
        } else {
            ProductAffiliate::create([
                'product_id' => $product->id,
                'affiliate_user_id' => auth()->id(),
                'affiliate_code' => $this->generateAffiliateCode(),
                'clicks' => 0,
                'status' => 'active',
            ]);
        }

        return redirect()
            ->route('affiliates.my')
            ->with('success', 'Você se afiliou ao produto com sucesso.');
    }

    public function myAffiliations()
    {
        $affiliations = ProductAffiliate::with(['product.images', 'product.user'])
            ->where('affiliate_user_id', auth()->id())
            ->latest()
            ->get();

        return view('affiliates.my', compact('affiliations'));
    }

    public function cancel(ProductAffiliate $affiliation)
    {
        if ($affiliation->affiliate_user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para cancelar esta afiliação.');
        }

        $affiliation->update([
            'status' => 'canceled',
        ]);

        return back()->with('success', 'Afiliação cancelada com sucesso.');
    }

    private function generateAffiliateCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (ProductAffiliate::where('affiliate_code', $code)->exists());

        return $code;
    }
}