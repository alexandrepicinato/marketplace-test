@extends('layouts.app')

@section('title', $product->name . ' - Indicação de Afiliado')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $product->name }}</h1>
            <p>
                Produto indicado por
                <strong>{{ $affiliate->affiliate->name ?? 'Afiliado' }}</strong>
            </p>
        </div>

        <a href="{{ route('home') }}" class="btn btn-secondary">
            Voltar à loja
        </a>
    </div>

    <div class="alert alert-success">
        Você chegou até este produto através de um link de afiliado.
        Se finalizar a compra, a comissão será vinculada ao afiliado
        <strong>{{ $affiliate->affiliate->name ?? 'Afiliado' }}</strong>.
    </div>

    <div class="card">
        <div class="public-product-grid">
            <div>
                @if ($product->images->count() > 0)
                    <img
                        src="{{ asset('storage/' . $product->images->first()->path) }}"
                        alt="{{ $product->name }}"
                        class="main-product-image"
                    >

                    @if ($product->images->count() > 1)
                        <div class="image-grid" style="margin-top: 16px;">
                            @foreach ($product->images as $image)
                                <div class="image-card">
                                    <img
                                        src="{{ asset('storage/' . $image->path) }}"
                                        alt="{{ $product->name }}"
                                    >
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div style="height: 360px; border-radius: 16px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                        Produto sem imagem
                    </div>
                @endif
            </div>

            <div>
                <div style="margin-bottom: 16px;">
                    <span class="badge badge-active">
                        Produto vindo de afiliado
                    </span>
                </div>

                <h2 style="margin-top: 0;">{{ $product->name }}</h2>

                <p class="muted">
                    {{ $product->description ?: 'Produto sem descrição.' }}
                </p>

                <div class="price">
                    R$ {{ number_format($product->price, 2, ',', '.') }}
                </div>

                <p>
                    <strong>Vendedor:</strong>
                    {{ $product->user->name ?? 'Vendedor' }}
                </p>

                <p>
                    <strong>Afiliado:</strong>
                    {{ $affiliate->affiliate->name ?? 'Afiliado' }}
                </p>

                <p>
                    <strong>Código do afiliado:</strong>
                    {{ $affiliate->affiliate_code }}
                </p>

                <p>
                    <strong>Estoque:</strong>
                    {{ $product->stock }}
                </p>

                @if ($product->accepts_affiliation)
                    <p>
                        <strong>Comissão do afiliado:</strong>
                        {{ number_format($product->commission_percentage, 2, ',', '.') }}%
                    </p>
                @endif

                <div class="actions" style="margin-top: 24px;">
                    @auth
                        <a
                            href="{{ route('checkout.show', ['product' => $product->slug]) }}"
                            class="btn btn-primary"
                        >
                            Comprar com indicação
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Entrar para comprar
                        </a>

                        <a href="{{ route('register') }}" class="btn btn-secondary">
                            Criar conta
                        </a>
                    @endauth
                </div>

                <div style="margin-top: 24px; padding: 16px; background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;">
                    <p style="margin-top: 0;">
                        <strong>Resumo da indicação</strong>
                    </p>

                    <p class="muted" style="margin-bottom: 0;">
                        Esta página foi carregada com o código
                        <strong>{{ $affiliate->affiliate_code }}</strong>.
                        O sistema salvou esta referência na sessão para vincular a comissão caso a compra seja finalizada.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection