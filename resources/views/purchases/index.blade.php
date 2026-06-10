@extends('layouts.app')

@section('title', 'Minhas Compras')

@section('content')
    <div class="page-header">
        <div>
            <h1>Minhas Compras</h1>
            <p>Pedidos realizados pela sua conta.</p>
        </div>

        <a href="{{ route('home') }}" class="btn btn-secondary">
            Continuar comprando
        </a>
    </div>

    <div class="card">
        @if ($orders->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Produto</th>
                        <th>Vendedor</th>
                        <th>Total</th>
                        <th>Status</th>
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
                                {{ $order->seller->name ?? '-' }}
                            </td>

                            <td>
                                R$ {{ number_format($order->subtotal, 2, ',', '.') }}
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
                                <a href="{{ route('purchases.show', $order) }}" class="btn btn-secondary btn-small">
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
                    Você ainda não fez nenhuma compra
                </h2>

                <p class="muted">
                    Quando você comprar um produto, ele aparecerá aqui.
                </p>

                <div style="margin-top: 20px;">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        Ver produtos
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection