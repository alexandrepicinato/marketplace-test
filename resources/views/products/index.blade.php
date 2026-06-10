@extends('layouts.app')

@section('title', 'Meus Produtos')

@section('content')
    <div class="page-header">
        <div>
            <h1>Meus Produtos</h1>
            <p>Produtos cadastrados pela sua conta.</p>
        </div>

        <a href="{{ route('products.create') }}" class="btn btn-primary">
            Cadastrar Produto
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
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Afiliação</th>
                        <th>Status</th>
                        <th>Ações</th>
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
                                        {{ \Illuminate\Support\Str::limit($product->description, 60) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </td>

                            <td>
                                {{ $product->stock }}
                            </td>

                            <td>
                                @if ($product->accepts_affiliation)
                                    <span class="badge badge-active">
                                        {{ number_format($product->commission_percentage, 2, ',', '.') }}%
                                    </span>
                                @else
                                    <span class="badge badge-inactive">
                                        Não aceita
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if ($product->active)
                                    <span class="badge badge-active">Ativo</span>
                                @else
                                    <span class="badge badge-inactive">Inativo</span>
                                @endif
                            </td>

                            <td>
                                <div class="inline-actions">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-small">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('products.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-small"
                                            onclick="return confirm('Deseja excluir este produto?')"
                                        >
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px;">
                <h2 style="margin-bottom: 8px;">Nenhum produto cadastrado</h2>

                <p class="muted">
                    Comece cadastrando seu primeiro produto no marketplace.
                </p>

                <div style="margin-top: 20px;">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        Cadastrar primeiro produto
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection