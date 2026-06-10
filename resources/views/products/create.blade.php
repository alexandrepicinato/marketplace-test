@extends('layouts.app')

@section('title', 'Cadastrar Produto')

@section('content')
    <div class="page-header">
        <div>
            <h1>Cadastrar Produto</h1>
            <p>Preencha as informações principais do produto.</p>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>Nome do produto</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        placeholder="Ex: Camiseta Premium"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Descrição</label>
                    <textarea
                        name="description"
                        class="form-control"
                        placeholder="Descreva o produto de forma clara"
                    >{{ old('description') }}</textarea>
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
                            value="{{ old('price') }}"
                            placeholder="0.00"
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
                            value="{{ old('stock', 0) }}"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label>Imagens do produto</label>
                    <input
                        type="file"
                        name="images[]"
                        class="form-control"
                        multiple
                        accept="image/*"
                    >

                    <p class="muted">
                        Você pode enviar até 5 imagens. Formatos: JPG, PNG ou WEBP.
                    </p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Produto ativo?</label>

                        <label class="checkbox">
                            <input type="checkbox" name="active" value="1" checked>
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
                                {{ old('accepts_affiliation') ? 'checked' : '' }}
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
                        value="{{ old('commission_percentage') }}"
                        placeholder="Ex: 10"
                    >
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">
                        Salvar Produto
                    </button>

                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection