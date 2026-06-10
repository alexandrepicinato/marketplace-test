@extends('layouts.app')

@section('title', 'Admin - Ganhos de Afiliados')

@section('content')
    <div class="page-header">
        <div>
            <h1>Ganhos de Afiliados</h1>
            <p>Valide, rejeite ou marque comissões como pagas.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div class="product-grid" style="margin-bottom: 24px;">
        <div class="card">
            <h3>Total</h3>
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
                        <th>Afiliado</th>
                        <th>Comprador</th>
                        <th>Venda</th>
                        <th>Comissão</th>
                        <th>Status</th>
                        <th>Validar</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>

                            <td>
                                <strong>{{ $order->product->name ?? '-' }}</strong>
                                <div class="muted">
                                    Vendedor: {{ $order->seller->name ?? '-' }}
                                </div>
                            </td>

                            <td>
                                {{ $order->affiliate->name ?? '-' }}
                                <div class="muted">
                                    {{ $order->affiliate->email ?? '-' }}
                                </div>
                            </td>

                            <td>{{ $order->buyer->name ?? '-' }}</td>

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

                                @if ($order->commissionValidator)
                                    <div class="muted">
                                        Validado por: {{ $order->commissionValidator->name }}
                                    </div>
                                @endif

                                @if ($order->commission_paid_at)
                                    <div class="muted">
                                        Pago em: {{ $order->commission_paid_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <form method="POST" action="{{ route('admin.affiliate.earnings.update', $order) }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-grid" style="gap: 8px;">
                                        <select name="commission_status" class="form-control">
                                            <option value="pending" {{ $order->commission_status === 'pending' ? 'selected' : '' }}>
                                                Pendente
                                            </option>

                                            <option value="approved" {{ $order->commission_status === 'approved' ? 'selected' : '' }}>
                                                Aprovar
                                            </option>

                                            <option value="rejected" {{ $order->commission_status === 'rejected' ? 'selected' : '' }}>
                                                Rejeitar
                                            </option>

                                            <option value="paid" {{ $order->commission_status === 'paid' ? 'selected' : '' }}>
                                                Marcar como paga
                                            </option>
                                        </select>

                                        <textarea
                                            name="commission_notes"
                                            class="form-control"
                                            placeholder="Observação sobre a comissão"
                                        >{{ $order->commission_notes }}</textarea>

                                        <button type="submit" class="btn btn-primary btn-small">
                                            Salvar
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="muted">Nenhuma comissão de afiliado encontrada.</p>
        @endif
    </div>
@endsection