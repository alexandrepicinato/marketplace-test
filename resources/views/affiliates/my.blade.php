@extends('layouts.app')

@section('title', 'Minhas Afiliações')

@section('content')
    <div class="page-header">
        <div>
            <h1>Minhas Afiliações</h1>
            <p>Produtos aos quais você se afiliou.</p>
        </div>

        <a href="{{ route('affiliates.index') }}" class="btn btn-primary">
            Buscar Produtos
        </a>
    </div>

    <div class="card">
        @if ($affiliations->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Produto</th>
                            <th>Vendedor</th>
                            <th>Preço</th>
                            <th>Comissão</th>
                            <th>Cliques</th>
                            <th>Link de afiliado</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($affiliations as $affiliation)
                            @php
                                $product = $affiliation->product;

                                $affiliateLink = null;

                                if ($affiliation->affiliate_code && $affiliation->status === 'active') {
                                    $affiliateLink = route('affiliates.redirect', [
                                        'affiliation' => $affiliation->id,
                                        'affiliateCode' => $affiliation->affiliate_code,
                                    ]);
                                }
                            @endphp

                            <tr>
                                <td>
                                    @if ($product && $product->images->first())
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
                                    @if ($product)
                                        <strong>{{ $product->name }}</strong>

                                        @if ($product->description)
                                            <div class="muted">
                                                {{ \Illuminate\Support\Str::limit($product->description, 60) }}
                                            </div>
                                        @endif

                                        <div class="muted" style="margin-top: 6px;">
                                            <a
                                                href="{{ route('products.public.show', ['product' => $product->slug]) }}"
                                                target="_blank"
                                            >
                                                Ver página normal
                                            </a>
                                        </div>
                                    @else
                                        <strong>Produto removido</strong>
                                    @endif
                                </td>

                                <td>
                                    @if ($product && $product->user)
                                        {{ $product->user->name }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if ($product)
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if ($product && $product->commission_percentage)
                                        {{ number_format($product->commission_percentage, 2, ',', '.') }}%
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $affiliation->clicks ?? 0 }}
                                </td>

                                <td>
                                    @if ($affiliateLink)
                                        <input
                                            type="text"
                                            class="form-control"
                                            value="{{ $affiliateLink }}"
                                            readonly
                                            onclick="this.select()"
                                            style="min-width: 260px;"
                                        >

                                        <div class="actions" style="margin-top: 8px;">
                                            <a
                                                href="{{ $affiliateLink }}"
                                                target="_blank"
                                                class="btn btn-secondary btn-small"
                                            >
                                                Abrir como afiliado
                                            </a>
                                        </div>

                                        <div class="muted" style="margin-top: 6px;">
                                            Clique no campo para selecionar e copiar o link.
                                        </div>
                                    @else
                                        <span class="muted">Link indisponível</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($affiliation->status === 'active')
                                        <span class="badge badge-active">
                                            Ativa
                                        </span>
                                    @else
                                        <span class="badge badge-inactive">
                                            Cancelada
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($affiliation->status === 'active')
                                        <form method="POST" action="{{ route('affiliates.cancel', $affiliation) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-small"
                                                onclick="return confirm('Deseja cancelar esta afiliação?')"
                                            >
                                                Cancelar
                                            </button>
                                        </form>
                                    @else
                                        <span class="muted">
                                            Sem ação
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px;">
                <h2 style="margin-bottom: 8px;">
                    Você ainda não tem afiliações
                </h2>

                <p class="muted">
                    Acesse a lista de produtos disponíveis e escolha um produto para se afiliar.
                </p>

                <div style="margin-top: 20px;">
                    <a href="{{ route('affiliates.index') }}" class="btn btn-primary">
                        Ver produtos disponíveis
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection