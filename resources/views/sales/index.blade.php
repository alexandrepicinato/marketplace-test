@extends('layouts.app')

@section('title', 'Minhas Vendas')

@section('content')
    <div class="page-header">
        <div>
            <h1>Minhas Vendas</h1>
            <p>Acompanhe os pedidos recebidos dos seus produtos.</p>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            Meus produtos
        </a>
    </div>

    <div class="product-grid" style="margin-bottom: 24px;">
        <div class="card">
            <h3>Total de vendas</h3>
            <div class="price">{{ $summary['total_sales'] }}</div>
        </div>

        <div class="card">
            <h3>Faturamento bruto</h3>
            <div class="price">
                R$ {{ number_format($summary['gross_revenue'], 2, ',', '.') }}
            </div>
        </div>

        <div class="card">
            <h3>Comissões afiliados</h3>
            <div class="price">
                R$ {{ number_format($summary['affiliate_commissions'], 2, ',', '.') }}
            </div>
        </div>

        <div class="card">
            <h3>Receita líquida estimada</h3>
            <div class="price">
                R$ {{ number_format($summary['net_revenue'], 2, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="product-grid" style="margin-bottom: 24px;">
        <div class="card">
            <h3>Pendente</h3>
            <div class="price">{{ $summary['pending'] }}</div>
        </div>

        <div class="card">
            <h3>Pago</h3>
            <div class="price">{{ $summary['paid'] }}</div>
        </div>

        <div class="card">
            <h3>Processando</h3>
            <div class="price">{{ $summary['processing'] }}</div>
        </div>

        <div class="card">
            <h3>Enviado</h3>
            <div class="price">{{ $summary['shipped'] }}</div>
        </div>

        <div class="card">
            <h3>Entregue</h3>
            <div class="price">{{ $summary['delivered'] }}</div>
        </div>

        <div class="card">
            <h3>Cancelado</h3>
            <div class="price">{{ $summary['canceled'] }}</div>
        </div>
    </div>

    <div class="card">
        @if ($orders->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Produto</th>
                        <th>Comprador</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pagamento</th>
                        <th>Afiliado</th>
                        <th>Rastreio</th>
                        <th>Data</th>
                        <th>Ação</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>
                                #{{ $order->id }}
                            </td>

                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
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
                                        <strong>{{ $order->product->name ?? 'Produto removido' }}</strong>

                                        <div class="muted">
                                            Quantidade: {{ $order->quantity }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $order->buyer->name ?? '-' }}

                                @if ($order->buyer)
                                    <div class="muted">
                                        {{ $order->buyer->email }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <strong>
                                    R$ {{ number_format($order->subtotal, 2, ',', '.') }}
                                </strong>
                            </td>

                            <td>
                                @if ($order->status === 'pending')
                                    <span class="badge badge-inactive">Pendente</span>
                                @elseif ($order->status === 'paid')
                                    <span class="badge badge-active">Pago</span>
                                @elseif ($order->status === 'processing')
                                    <span class="badge badge-active">Processando</span>
                                @elseif ($order->status === 'shipped')
                                    <span class="badge badge-active">Enviado</span>
                                @elseif ($order->status === 'delivered')
                                    <span class="badge badge-active">Entregue</span>
                                @elseif ($order->status === 'canceled')
                                    <span class="badge badge-inactive">Cancelado</span>
                                @elseif ($order->status === 'refunded')
                                    <span class="badge badge-inactive">Reembolsado</span>
                                @else
                                    <span class="badge badge-inactive">{{ $order->status }}</span>
                                @endif
                            </td>

                            <td>
                                @if ($order->paymentInfo)
                                    {{ $order->paymentInfo->payment_method }}

                                    <div class="muted">
                                        {{ $order->paymentInfo->payer_name }}
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                @if ($order->affiliate)
                                    {{ $order->affiliate->name }}

                                    <div class="muted">
                                        Comissão:
                                        R$ {{ number_format($order->commission_amount, 2, ',', '.') }}
                                    </div>

                                    <div class="muted">
                                        Status: {{ $order->commission_status }}
                                    </div>
                                @else
                                    <span class="muted">Sem afiliado</span>
                                @endif
                            </td>

                            <td>
                                @if ($order->tracking_status)
                                    <strong>{{ $order->tracking_status }}</strong>

                                    @if ($order->tracking_code)
                                        <div class="muted">
                                            {{ $order->tracking_code }}
                                        </div>
                                    @endif
                                @else
                                    <span class="muted">Sem rastreio</span>
                                @endif
                            </td>

                            <td>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <a href="{{ route('sales.show', $order) }}" class="btn btn-secondary btn-small">
                                    Ver detalhes
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px;">
                <h2 style="margin-bottom: 8px;">
                    Você ainda não recebeu vendas
                </h2>

                <p class="muted">
                    Quando alguém comprar um dos seus produtos, a venda aparecerá aqui.
                </p>

                <div style="margin-top: 20px;">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        Cadastrar produto
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection