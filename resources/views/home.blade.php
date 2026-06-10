@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
    <div class="page-header">
        <div>
            <h1>Produtos disponíveis</h1>
            <p>Veja os produtos do marketplace. Cadastro só é necessário para comprar ou se afiliar.</p>
        </div>
    </div>

    @if ($products->count() > 0)
        <div class="product-grid">
            @foreach ($products as $product)
                <div class="product-card">
                    @if ($product->images->first())
                        <img src="{{ asset('storage/' . $product->images->first()->path) }}" alt="{{ $product->name }}">
                    @else
                        <div style="height: 180px; display: flex; align-items: center; justify-content: center; color: #9ca3af; background: #f3f4f6;">
                            Sem imagem
                        </div>
                    @endif

                    <div class="product-card-body">
                        <h3 style="margin-top: 0;">{{ $product->name }}</h3>

                        <p class="muted">
                            {{ \Illuminate\Support\Str::limit($product->description, 80) }}
                        </p>

                        <div style="font-size: 22px; font-weight: bold; margin: 12px 0;">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </div>

                        @if ($product->accepts_affiliation)
                            <div style="margin-bottom: 12px;">
                                <span class="badge badge-active">
                                    Comissão {{ number_format($product->commission_percentage, 2, ',', '.') }}%
                                </span>
                            </div>
                        @endif

                        <div class="actions">
                            <a href="{{ route('products.public.show', ['product' => $product->slug]) }}" class="btn btn-primary">
                                Ver produto
                            </a>

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
                                        Afiliar-se
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <p class="muted">Nenhum produto disponível no momento.</p>
        </div>
    @endif
@endsection