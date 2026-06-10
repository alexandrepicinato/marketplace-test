@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Administrativo</h1>
            <p>Visão geral do software.</p>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-secondary">
                Sair do admin
            </button>
        </form>
    </div>

    <div class="product-grid" style="margin-bottom: 24px;">
        <div class="card">
            <h3>Usuários</h3>
            <div class="price">{{ $stats['users'] }}</div>
        </div>

        <div class="card">
            <h3>Produtos</h3>
            <div class="price">{{ $stats['products'] }}</div>
        </div>

        <div class="card">
            <h3>Produtos ativos</h3>
            <div class="price">{{ $stats['active_products'] }}</div>
        </div>

        <div class="card">
            <h3>Pedidos</h3>
            <div class="price">{{ $stats['orders'] }}</div>
        </div>

        <div class="card">
            <h3>Afiliações ativas</h3>
            <div class="price">{{ $stats['affiliations'] }}</div>
        </div>

        <div class="card">
            <h3>Faturamento bruto</h3>
            <div class="price">
                R$ {{ number_format($stats['revenue'], 2, ',', '.') }}
            </div>
        </div>
        <div class="card">
            <h3>Produtos pendentes</h3>
            <div class="price">{{ $stats['pending_products'] }}</div>
            <a href="{{ route('admin.settings') }}" class="btn btn-secondary">
                Configurações
            </a>
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Áreas administrativas</h2>

        <div class="actions">
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                Usuários
            </a>

            <a href="{{ route('admin.products') }}" class="btn btn-secondary">
                Produtos
            </a>

            <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
                Pedidos
            </a>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-top: 0;">Últimos pedidos</h2>

        @if ($latestOrders->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Comprador</th>
                        <th>Vendedor</th>
                        <th>Total</th>
                        <th>Afiliado</th>
                        <th>Status</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($latestOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->product->name ?? '-' }}</td>
                            <td>{{ $order->buyer->name ?? '-' }}</td>
                            <td>{{ $order->seller->name ?? '-' }}</td>
                            <td>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                            <td>{{ $order->affiliate->name ?? '-' }}</td>
                            <td>{{ $order->status }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="muted">Nenhum pedido registrado ainda.</p>
        @endif
    </div>
@endsection