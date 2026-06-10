@extends('layouts.app')

@section('title', 'Venda #' . $order->id)

@section('content')
    <div class="page-header">
        <div>
            <h1>Venda #{{ $order->id }}</h1>
            <p>Detalhes da venda recebida.</p>
        </div>

        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            Voltar às vendas
        </a>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Produto vendido</h2>

        <div style="display: flex; gap: 18px; align-items: center; flex-wrap: wrap;">
            @if ($order->product && $order->product->images->first())
                <img
                    src="{{ asset('storage/' . $order->product->images->first()->path) }}"
                    class="product-thumb"
                    alt="{{ $order->product->name }}"
                >
            @else
                <div class="empty-thumb">
                    Sem foto
                </div>
            @endif

            <div>
                <h3 style="margin: 0;">
                    {{ $order->product->name ?? 'Produto removido' }}
                </h3>

                <p class="muted">
                    Quantidade: {{ $order->quantity }}
                </p>
            </div>
        </div>
    </div>

    <div class="product-grid" style="margin-bottom: 24px;">
        <div class="card">
            <h3>Valor da venda</h3>
            <div class="price">
                R$ {{ number_format($order->subtotal, 2, ',', '.') }}
            </div>
        </div>

        <div class="card">
            <h3>Comissão afiliado</h3>
            <div class="price">
                R$ {{ number_format($order->commission_amount, 2, ',', '.') }}
            </div>
        </div>

        <div class="card">
            <h3>Líquido estimado</h3>
            <div class="price">
                R$ {{ number_format($order->subtotal - $order->commission_amount, 2, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Resumo da venda</h2>

        <p><strong>Status:</strong> {{ $order->status }}</p>
        <p><strong>Quantidade:</strong> {{ $order->quantity }}</p>
        <p><strong>Preço unitário:</strong> R$ {{ number_format($order->unit_price, 2, ',', '.') }}</p>
        <p><strong>Total:</strong> R$ {{ number_format($order->subtotal, 2, ',', '.') }}</p>
        <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>

        @if ($order->admin_notes)
            <p><strong>Observações administrativas:</strong> {{ $order->admin_notes }}</p>
        @endif
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Comprador</h2>

        @if ($order->buyer)
            <p><strong>Nome:</strong> {{ $order->buyer->name }}</p>
            <p><strong>E-mail:</strong> {{ $order->buyer->email }}</p>
            <p><strong>CPF:</strong> {{ $order->buyer->cpf ?? '-' }}</p>
            <p><strong>RG:</strong> {{ $order->buyer->rg ?? '-' }}</p>
        @else
            <p class="muted">Comprador não encontrado.</p>
        @endif
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Pagamento</h2>

        @if ($order->paymentInfo)
            <p><strong>Método:</strong> {{ $order->paymentInfo->payment_method }}</p>
            <p><strong>Pagador:</strong> {{ $order->paymentInfo->payer_name }}</p>
            <p><strong>CPF do pagador:</strong> {{ $order->paymentInfo->payer_cpf ?? '-' }}</p>
            <p><strong>Parcelas:</strong> {{ $order->paymentInfo->installments }}</p>

            @if ($order->paymentInfo->card_brand || $order->paymentInfo->card_last_four)
                <p>
                    <strong>Cartão:</strong>
                    {{ $order->paymentInfo->card_brand ?? '-' }}
                    final {{ $order->paymentInfo->card_last_four ?? '----' }}
                </p>
            @endif

            @if ($order->paymentInfo->notes)
                <p><strong>Observações do cliente:</strong> {{ $order->paymentInfo->notes }}</p>
            @endif
        @else
            <p class="muted">Nenhuma informação de pagamento encontrada.</p>
        @endif
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Endereço de entrega</h2>

        @if ($order->shippingAddress)
            <p>
                {{ $order->shippingAddress->street }},
                {{ $order->shippingAddress->number }}
                @if ($order->shippingAddress->complement)
                    - {{ $order->shippingAddress->complement }}
                @endif
            </p>

            <p>
                {{ $order->shippingAddress->neighborhood ?? '-' }},
                {{ $order->shippingAddress->city }}/{{ $order->shippingAddress->state }}
            </p>

            <p><strong>CEP:</strong> {{ $order->shippingAddress->zipcode ?? '-' }}</p>
            <p><strong>Telefone:</strong> {{ $order->shippingAddress->phone ?? '-' }}</p>
        @else
            <p class="muted">Endereço não encontrado.</p>
        @endif
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Afiliado</h2>

        @if ($order->affiliate)
            <p><strong>Afiliado:</strong> {{ $order->affiliate->name }}</p>
            <p><strong>E-mail:</strong> {{ $order->affiliate->email }}</p>
            <p><strong>Código:</strong> {{ $order->affiliate_code ?? '-' }}</p>
            <p><strong>Comissão:</strong> R$ {{ number_format($order->commission_amount, 2, ',', '.') }}</p>
            <p><strong>Status da comissão:</strong> {{ $order->commission_status }}</p>

            @if ($order->commission_notes)
                <p><strong>Observação da comissão:</strong> {{ $order->commission_notes }}</p>
            @endif
        @else
            <p class="muted">Esta venda não veio de afiliado.</p>
        @endif
    </div>

    <div class="card">
        <h2 style="margin-top: 0;">Rastreio</h2>

        @if ($order->tracking_status)
            <p><strong>Status atual:</strong> {{ $order->tracking_status }}</p>
            <p><strong>Código:</strong> {{ $order->tracking_code ?? '-' }}</p>
            <p><strong>Transportadora:</strong> {{ $order->tracking_carrier ?? '-' }}</p>

            @if ($order->tracking_url)
                <div style="margin: 16px 0;">
                    <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-secondary">
                        Abrir rastreio externo
                    </a>
                </div>
            @endif
        @else
            <p class="muted">O rastreio ainda não foi atualizado pelo administrador.</p>
        @endif

        @if ($order->trackingEvents->count() > 0)
            <h3>Histórico</h3>

            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Local</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($order->trackingEvents->sortByDesc('created_at') as $event)
                        <tr>
                            <td>
                                @if ($event->event_at)
                                    {{ \Carbon\Carbon::parse($event->event_at)->format('d/m/Y H:i') }}
                                @else
                                    {{ $event->created_at->format('d/m/Y H:i') }}
                                @endif
                            </td>

                            <td>{{ $event->status }}</td>
                            <td>{{ $event->location ?? '-' }}</td>
                            <td>{{ $event->description ?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection