@extends('layouts.app')

@section('title', 'Afiliar-se a Produtos')

@section('content')
    <div class="page-header">
        <div>
            <h1>Afiliar-se a Produtos</h1>
            <p>Escolha produtos de outros vendedores que permitem afiliação.</p>
        </div>

        <a href="{{ route('affiliates.my') }}" class="btn btn-secondary">
            Minhas Afiliações
        </a>
    </div>

    <div class="card">
        @if ($products->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Produto</th>
                        <th>Vendedor</th>
                        <th>Preço</th>
                        <th>Comissão</th>
                        <th>Ação</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                @if ($product->images->first())
                                    <img
                                        src="{{ asset('storage/' . $product->images->first()->path) }}"
                                        class="product-thumb"
                                        alt="{{ $product->name }}"
                                    >
                                @else
                                    <div class="empty-thumb">
                                        Sem foto
                                    </div>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $product->name }}</strong>

                                @if ($product->description)
                                    <div class="muted">
                                        {{ \Illuminate\Support\Str::limit($product->description, 70) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ $product->user->name ?? 'Vendedor' }}
                            </td>

                            <td>
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </td>

                            <td>
                                <span class="badge badge-active">
                                    {{ number_format($product->commission_percentage, 2, ',', '.') }}%
                                </span>
                            </td>

                            <td>
                                @if (in_array($product->id, $affiliatedProductIds))
                                    <span class="badge badge-active">
                                        Afiliado
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('affiliates.store', $product) }}">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-primary btn-small"
                                            onclick="return confirm('Deseja se afiliar a este produto?')"
                                        >
                                            Afiliar-se
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px;">
                <h2 style="margin-bottom: 8px;">Nenhum produto disponível</h2>

                <p class="muted">
                    Ainda não existem produtos ativos com afiliação autorizada por outros usuários.
                </p>
            </div>
        @endif
    </div>
@endsection