@extends('layouts.app')

@section('title', 'Ganhos como Afiliado')

@section('content')
    <div class="page-header">
        <div>
            <h1>Ganhos como Afiliado</h1>
            <p>Comissões geradas pelas suas indicações.</p>
        </div>

        <a href="{{ route('affiliates.my') }}" class="btn btn-secondary">
            Minhas afiliações
        </a>
    </div>

    <div class="product-grid" style="margin-bottom: 24px;">
        <div class="card">
            <h3>Total gerado</h3>
            <div class="price">R$ {{ number_format($summary['total'], 2, ',', '.') }}</div>
        </div>

        <div class="card">
            <h3>Pendente</h3>
            <div class="price">R$ {{ number_format($summary['pending'], 2, ',', '.') }}</div>
        </div>

        <div class="card">
            <h3>Aprovado</h3>
            <div class="price">R$ {{ number_format($summary['approved'], 2, ',', '.') }}</div>
        </div>

        <div class="card">
            <h3>Pago</h3>
            <div class="price">R$ {{ number_format($summary['paid'], 2, ',', '.') }}</div>
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
                        <th>Vendedor</th>
                        <th>Venda</th>
                        <th>Comissão</th>
                        <th>Status da comissão</th>
                        <th>Data</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>

                            <td>
                                <strong>{{ $order->product->name ?? '-' }}</strong>
                            </td>

                            <td>{{ $order->buyer->name ?? '-' }}</td>

                            <td>{{ $order->seller->name ?? '-' }}</td>

                            <td>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>

                            <td>
                                <strong>R$ {{ number_format($order->commission_amount, 2, ',', '.') }}</strong>

                                @if ($order->commission_percentage)
                                    <div class="muted">
                                        {{ number_format($order->commission_percentage, 2, ',', '.') }}%
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if ($order->commission_status === 'approved')
                                    <span class="badge badge-active">Aprovada</span>
                                @elseif ($order->commission_status === 'paid')
                                    <span class="badge badge-active">Paga</span>
                                @elseif ($order->commission_status === 'rejected')
                                    <span class="badge badge-inactive">Rejeitada</span>
                                @else
                                    <span class="badge badge-inactive">Pendente</span>
                                @endif

                                @if ($order->commission_notes)
                                    <div class="muted">
                                        {{ $order->commission_notes }}
                                    </div>
                                @endif
                            </td>

                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px;">
                <h2 style="margin-bottom: 8px;">
                    Nenhuma comissão gerada ainda
                </h2>

                <p class="muted">
                    Quando alguém comprar pelo seu link de afiliado, a comissão aparecerá aqui.
                </p>
            </div>
        @endif
    </div>
@endsection