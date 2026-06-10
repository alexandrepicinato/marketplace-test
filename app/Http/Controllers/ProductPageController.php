<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAffiliate;
use Illuminate\Http\Request;

class ProductPageController extends Controller
{
    public function show(Product $product, Request $request)
    {
        if (
            !$product->active ||
            $product->approval_status !== 'approved' ||
            $product->deleted_at
        ) {
            abort(404);
        }

        $product->load(['images', 'user']);

        $affiliate = null;

        if ($request->filled('ref')) {
            $affiliate = ProductAffiliate::with('affiliate')
                ->where('affiliate_code', $request->ref)
                ->where('product_id', $product->id)
                ->where('status', 'active')
                ->first();

            if ($affiliate) {
                session([
                    'affiliate_id' => $affiliate->id,
                    'affiliate_code' => $affiliate->affiliate_code,
                    'affiliate_product_id' => $product->id,
                    'affiliate_user_id' => $affiliate->affiliate_user_id,
                ]);

                return view('products.affiliate-show', compact('product', 'affiliate'));
            }
        }

        return view('products.show', compact('product', 'affiliate'));
    }

    public function affiliateRedirect(int $affiliation, string $affiliateCode)
    {
        $affiliation = ProductAffiliate::with('product')
            ->where('id', $affiliation)
            ->where('affiliate_code', $affiliateCode)
            ->where('status', 'active')
            ->firstOrFail();

        if (
            !$affiliation->product ||
            !$affiliation->product->active ||
            $affiliation->product->approval_status !== 'approved' ||
            $affiliation->product->deleted_at
        ) {
            abort(404);
        }

        $affiliation->increment('clicks');

        session([
            'affiliate_id' => $affiliation->id,
            'affiliate_code' => $affiliation->affiliate_code,
            'affiliate_product_id' => $affiliation->product_id,
            'affiliate_user_id' => $affiliation->affiliate_user_id,
        ]);

        return redirect()->route('products.public.show', [
            'product' => $affiliation->product->slug,
            'ref' => $affiliation->affiliate_code,
        ]);
    }
}