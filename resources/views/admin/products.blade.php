@extends('layouts.app')

@section('title', 'Admin - Produtos')

@section('content')
    <div class="page-header">
        <div>
            <h1>Produtos</h1>
            <p>Aprove, rejeite, restaure ou desative produtos.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div class="card">
        @if ($products->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Vendedor</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th>Aprovação</th>
                        <th>Ações</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>

                            <td>
                                <strong>{{ $product->name }}</strong>

                                @if ($product->deleted_at)
                                    <div class="muted">Desativado em {{ $product->deleted_at->format('d/m/Y H:i') }}</div>
                                @endif
                            </td>

                            <td>{{ $product->user->name ?? '-' }}</td>

                            <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>

                            <td>
                                @if ($product->active && !$product->deleted_at)
                                    <span class="badge badge-active">Ativo</span>
                                @else
                                    <span class="badge badge-inactive">Inativo</span>
                                @endif
                            </td>

                            <td>
                                @if ($product->approval_status === 'approved')
                                    <span class="badge badge-active">Aprovado</span>
                                @elseif ($product->approval_status === 'pending')
                                    <span class="badge badge-inactive">Pendente</span>
                                @else
                                    <span class="badge badge-inactive">Rejeitado</span>
                                @endif

                                @if ($product->rejection_reason)
                                    <div class="muted">{{ $product->rejection_reason }}</div>
                                @endif
                            </td>

                            <td>
                                <form method="POST" action="{{ route('admin.products.approval', $product) }}" style="margin-bottom: 8px;">
                                    @csrf
                                    @method('PATCH')

                                    <select name="approval_status" class="form-control" style="margin-bottom: 8px;">
                                        <option value="pending" {{ $product->approval_status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                        <option value="approved" {{ $product->approval_status === 'approved' ? 'selected' : '' }}>Aprovado</option>
                                        <option value="rejected" {{ $product->approval_status === 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                                    </select>

                                    <input
                                        type="text"
                                        name="rejection_reason"
                                        class="form-control"
                                        placeholder="Motivo da rejeição"
                                        value="{{ $product->rejection_reason }}"
                                        style="margin-bottom: 8px;"
                                    >

                                    <button class="btn btn-primary btn-small" type="submit">
                                        Alterar aprovação
                                    </button>
                                </form>

                                <div class="inline-actions">
                                    @if ($product->deleted_at)
                                        <form method="POST" action="{{ route('admin.products.restore', $product) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-secondary btn-small">
                                                Restaurar
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.products.disable', $product) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-small"
                                                onclick="return confirm('Desativar produto sem apagar do banco?')"
                                            >
                                                Desativar
                                            </button>
                                        </form>
                                    @endif

                                    @if (!$product->deleted_at && $product->approval_status === 'approved')
                                        <a
                                            href="{{ route('products.public.show', ['product' => $product->slug]) }}"
                                            target="_blank"
                                            class="btn btn-secondary btn-small"
                                        >
                                            Abrir
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="muted">Nenhum produto encontrado.</p>
        @endif
    </div>
@endsection