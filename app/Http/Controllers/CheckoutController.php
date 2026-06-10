<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAffiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(Product $product)
    {
    if (!$product->active || $product->approval_status !== 'approved' || $product->deleted_at) {
        abort(404);
    }

        $product->load(['images', 'user']);

        return view('checkout.show', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        if (!$product->active) {
            abort(404);
        }

        $dados = $request->validate([
            'cpf' => ['required', 'string', 'max:30'],
            'rg' => ['required', 'string', 'max:30'],

            'phone' => ['required', 'string', 'max:30'],

            'zipcode' => ['nullable', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:50'],
            'complement' => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:2'],

            'quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'string', 'max:50'],
            'payer_name' => ['required', 'string', 'max:255'],
            'payer_cpf' => ['nullable', 'string', 'max:30'],
            'card_last_four' => ['nullable', 'string', 'max:4'],
            'card_brand' => ['nullable', 'string', 'max:50'],
            'installments' => ['nullable', 'integer', 'min:1', 'max:12'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = auth()->user();

        $affiliate = null;

        if (session('affiliate_code') && session('affiliate_product_id') == $product->id) {
            $affiliate = ProductAffiliate::where('affiliate_code', session('affiliate_code'))
                ->where('product_id', $product->id)
                ->where('status', 'active')
                ->first();
        }

        $quantity = (int) $dados['quantity'];
        $subtotal = $product->price * $quantity;

        $commissionPercentage = $affiliate ? $product->commission_percentage : null;
        $commissionAmount = $affiliate && $commissionPercentage
            ? ($subtotal * $commissionPercentage / 100)
            : 0;

        $order = DB::transaction(function () use ($user, $product, $dados, $quantity, $subtotal, $affiliate, $commissionPercentage, $commissionAmount) {
            $user->update([
                'cpf' => $dados['cpf'],
                'rg' => $dados['rg'],
            ]);

            $address = $user->addresses()->create([
                'label' => 'Checkout',
                'zipcode' => $dados['zipcode'] ?? null,
                'street' => $dados['street'],
                'number' => $dados['number'],
                'complement' => $dados['complement'] ?? null,
                'neighborhood' => $dados['neighborhood'] ?? null,
                'city' => $dados['city'],
                'state' => strtoupper($dados['state']),
                'is_default' => !$user->addresses()->exists(),
            ]);

            $phone = $user->phones()->create([
                'label' => 'Checkout',
                'phone' => $dados['phone'],
                'is_default' => !$user->phones()->exists(),
            ]);

            $order = Order::create([
                'buyer_user_id' => $user->id,
                'seller_user_id' => $product->user_id,
                'product_id' => $product->id,
                'affiliate_user_id' => $affiliate?->affiliate_user_id,
                'affiliate_code' => $affiliate?->affiliate_code,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
                'commission_percentage' => $commissionPercentage,
                'commission_amount' => $commissionAmount,
                'status' => 'pending',
            ]);

            $order->paymentInfo()->create([
                'payment_method' => $dados['payment_method'],
                'payer_name' => $dados['payer_name'],
                'payer_cpf' => $dados['payer_cpf'] ?? $dados['cpf'],
                'card_last_four' => $dados['card_last_four'] ?? null,
                'card_brand' => $dados['card_brand'] ?? null,
                'installments' => $dados['installments'] ?? 1,
                'notes' => $dados['notes'] ?? null,
            ]);

            $order->shippingAddress()->create([
                'zipcode' => $address->zipcode,
                'street' => $address->street,
                'number' => $address->number,
                'complement' => $address->complement,
                'neighborhood' => $address->neighborhood,
                'city' => $address->city,
                'state' => $address->state,
                'phone' => $phone->phone,
            ]);

            return $order;
        });

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        if ($order->buyer_user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['product.images', 'paymentInfo', 'shippingAddress', 'affiliate']);

        return view('checkout.success', compact('order'));
    }
}