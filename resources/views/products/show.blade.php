@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $product->name }}</h1>
            <p>Produto vendido por {{ $product->user->name ?? 'Vendedor' }}</p>
        </div>

        <a href="{{ route('home') }}" class="btn btn-secondary">
            Voltar à loja
        </a>
    </div>

    @if ($affiliate)
        <div class="alert alert-success">
            Você está acessando este produto pelo link de afiliado de
            <strong>{{ $affiliate->affiliate->name ?? 'Afiliado' }}</strong>.
        </div>
    @endif

    <div class="card">
        <div class="public-product-grid">
            <div>
                @if ($product->images->count() > 0)
                    <img src="{{ asset('storage/' . $product->images->first()->path) }}" alt="{{ $product->name }}" class="main-product-image">

                    @if ($product->images->count() > 1)
                        <div class="image-grid" style="margin-top: 16px;">
                            @foreach ($product->images as $image)
                                <div class="image-card">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}">
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
                <h2 style="margin-top: 0;">{{ $product->name }}</h2>

                <p class="muted">
                    {{ $product->description ?: 'Produto sem descrição.' }}
                </p>

                <div class="price">
                    R$ {{ number_format($product->price, 2, ',', '.') }}
                </div>

                <p><strong>Estoque:</strong> {{ $product->stock }}</p>

                @if ($product->accepts_affiliation)
                    <p>
                        <strong>Comissão para afiliados:</strong>
                        {{ number_format($product->commission_percentage, 2, ',', '.') }}%
                    </p>
                @endif

                <div class="actions" style="margin-top: 24px;">
                    @auth
                        <a href="{{ route('checkout.show', ['product' => $product->slug]) }}" class="btn btn-primary">
                            Comprar agora
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Entrar para comprar
                        </a>
                    @endauth

                    @auth
                        @if ($product->user_id !== auth()->id() && $product->accepts_affiliation)
                            <form method="POST" action="{{ route('affiliates.store', $product) }}">
                                @csrf

                                <button type="submit" class="btn btn-secondary">
                                    Afiliar-se
                                </button>
                            </form>
                        @endif
                    @else
                        @if ($product->accepts_affiliation)
                            <a href="{{ route('login') }}" class="btn btn-secondary">
                                Entrar para afiliar-se
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection