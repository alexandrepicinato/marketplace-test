@extends('layouts.app')

@section('title', 'Pedido criado')

@section('content')
    <div class="page-header">
        <div>
            <h1>Pedido criado</h1>
            <p>Seu pedido foi registrado com sucesso.</p>
        </div>

        <a href="{{ route('home') }}" class="btn btn-secondary">
            Voltar à loja
        </a>
    </div>

    <div class="card">
        <h2 style="margin-top: 0;">Resumo do pedido #{{ $order->id }}</h2>

        <p><strong>Produto:</strong> {{ $order->product->name }}</p>
        <p><strong>Quantidade:</strong> {{ $order->quantity }}</p>
        <p><strong>Total:</strong> R$ {{ number_format($order->subtotal, 2, ',', '.') }}</p>
        <p><strong>Status:</strong> {{ $order->status }}</p>

        @if ($order->affiliate)
            <p>
                <strong>Afiliado:</strong> {{ $order->affiliate->name }}
            </p>

            <p>
                <strong>Comissão:</strong>
                R$ {{ number_format($order->commission_amount, 2, ',', '.') }}
            </p>
        @endif

        <p class="muted">
            As informações de pagamento foram salvas para análise administrativa.
        </p>
        @if ($order->tracking_status)
            <div class="card" style="margin-top: 24px;">
                <h2 style="margin-top: 0;">Rastreio</h2>

                <p><strong>Status:</strong> {{ $order->tracking_status }}</p>
                <p><strong>Código:</strong> {{ $order->tracking_code ?? '-' }}</p>
                <p><strong>Transportadora:</strong> {{ $order->tracking_carrier ?? '-' }}</p>

                @if ($order->tracking_url)
                    <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-secondary">
                        Abrir rastreio
                    </a>
                @endif
            </div>
        @endif
    </div>
@endsection