@extends('layouts.app')

@section('title', 'Admin - Pedidos')

@section('content')
    <div class="page-header">
        <div>
            <h1>Pedidos</h1>
            <p>Altere status, veja dados de checkout e acesse rastreio.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div class="card">
        @if ($orders->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Comprador</th>
                        <th>Vendedor</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Rastreio</th>
                        <th>Alterar</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>

                            <td>{{ $order->product->name ?? '-' }}</td>

                            <td>
                                {{ $order->buyer->name ?? '-' }}
                                <div class="muted">
                                    {{ $order->buyer->email ?? '-' }}
                                </div>
                            </td>

                            <td>{{ $order->seller->name ?? '-' }}</td>

                            <td>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>

                            <td>
                                <span class="badge badge-active">
                                    {{ $order->status }}
                                </span>
                            </td>

                            <td>
                                @if ($order->tracking_status)
                                    <strong>{{ $order->tracking_status }}</strong>
                                    <div class="muted">{{ $order->tracking_code ?? '-' }}</div>
                                @else
                                    <span class="muted">Sem rastreio</span>
                                @endif

                                <div style="margin-top: 8px;">
                                    <a href="{{ route('admin.orders.tracking', $order) }}" class="btn btn-secondary btn-small">
                                        Atualizar rastreio
                                    </a>
                                </div>
                            </td>

                            <td>
                                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                                    @csrf
                                    @method('PATCH')

                                    <select name="status" class="form-control" style="margin-bottom: 8px;">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                        <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Pago</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processando</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregue</option>
                                        <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Cancelado</option>
                                        <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                                    </select>

                                    <textarea
                                        name="admin_notes"
                                        class="form-control"
                                        placeholder="Observações administrativas"
                                        style="margin-bottom: 8px;"
                                    >{{ $order->admin_notes }}</textarea>

                                    <button type="submit" class="btn btn-primary btn-small">
                                        Salvar status
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="muted">Nenhum pedido encontrado.</p>
        @endif
    </div>
@endsection