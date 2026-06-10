@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
    <div class="page-header">
        <div>
            <h1>Editar Produto</h1>
            <p>Atualize as informações e imagens do produto.</p>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    @if ($product->images->count() > 0)
        <div class="card" style="margin-bottom: 24px;">
            <h2 style="margin-top: 0;">Imagens atuais</h2>

            <div class="image-grid">
                @foreach ($product->images as $image)
                    <div class="image-card">
                        <img
                            src="{{ asset('storage/' . $image->path) }}"
                            alt="{{ $product->name }}"
                        >

                        <form method="POST" action="{{ route('products.images.destroy', $image) }}">
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-danger btn-small"
                                style="width: 100%;"
                                onclick="return confirm('Remover esta imagem?')"
                            >
                                Remover imagem
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Nome do produto</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $product->name) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Descrição</label>
                    <textarea
                        name="description"
                        class="form-control"
                    >{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Preço</label>
                        <input
                            type="number"
                            name="price"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="{{ old('price', $product->price) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Estoque</label>
                        <input
                            type="number"
                            name="stock"
                            class="form-control"
                            min="0"
                            value="{{ old('stock', $product->stock) }}"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label>Adicionar novas imagens</label>
                    <input
                        type="file"
                        name="images[]"
                        class="form-control"
                        multiple
                        accept="image/*"
                    >

                    <p class="muted">
                        As novas imagens serão adicionadas à galeria atual.
                    </p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Produto ativo?</label>

                        <label class="checkbox">
                            <input
                                type="checkbox"
                                name="active"
                                value="1"
                                {{ old('active', $product->active) ? 'checked' : '' }}
                            >
                            Produto disponível no sistema
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Permitir afiliação?</label>

                        <label class="checkbox">
                            <input
                                type="checkbox"
                                name="accepts_affiliation"
                                value="1"
                                {{ old('accepts_affiliation', $product->accepts_affiliation) ? 'checked' : '' }}
                            >
                            Outros usuários podem se afiliar
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Comissão do afiliado (%)</label>
                    <input
                        type="number"
                        name="commission_percentage"
                        class="form-control"
                        step="0.01"
                        min="0"
                        max="100"
                        value="{{ old('commission_percentage', $product->commission_percentage) }}"
                        placeholder="Ex: 10"
                    >
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">
                        Atualizar Produto
                    </button>

                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection