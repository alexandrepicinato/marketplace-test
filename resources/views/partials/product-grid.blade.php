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

                    <div class="actions">
                        <a href="{{ route('products.public.show', ['product' => $product->slug]) }}" class="btn btn-primary">
                            Ver produto
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card">
        <p class="muted">Nenhum produto disponível.</p>
    </div>
@endif