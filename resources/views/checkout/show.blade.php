@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="page-header">
        <div>
            <h1>Checkout</h1>
            <p>Complete seus dados para finalizar o pedido.</p>
        </div>

        <a href="{{ route('products.public.show', ['product' => $product->slug]) }}" class="btn btn-secondary">
            Voltar ao produto
        </a>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Produto</h2>

        <p><strong>{{ $product->name }}</strong></p>
        <p class="muted">Vendedor: {{ $product->user->name ?? 'Vendedor' }}</p>
        <p style="font-size: 24px; font-weight: bold;">
            R$ {{ number_format($product->price, 2, ',', '.') }}
        </p>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('checkout.store', ['product' => $product->slug]) }}">
            @csrf

            <div class="form-grid">
                <h2 style="margin: 0;">Dados pessoais</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label>CPF</label>
                        <input type="text" name="cpf" class="form-control" value="{{ old('cpf', auth()->user()->cpf) }}" required>
                    </div>

                    <div class="form-group">
                        <label>RG</label>
                        <input type="text" name="rg" class="form-control" value="{{ old('rg', auth()->user()->rg) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Telefone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                </div>

                <h2 style="margin: 0;">Endereço</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label>CEP</label>
                        <input type="text" name="zipcode" class="form-control" value="{{ old('zipcode') }}">
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <input type="text" name="state" class="form-control" maxlength="2" value="{{ old('state') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Rua</label>
                    <input type="text" name="street" class="form-control" value="{{ old('street') }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Número</label>
                        <input type="text" name="number" class="form-control" value="{{ old('number') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Complemento</label>
                        <input type="text" name="complement" class="form-control" value="{{ old('complement') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Bairro</label>
                        <input type="text" name="neighborhood" class="form-control" value="{{ old('neighborhood') }}">
                    </div>

                    <div class="form-group">
                        <label>Cidade</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                    </div>
                </div>

                <h2 style="margin: 0;">Pagamento</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity', 1) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Método de pagamento</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="pix">Pix</option>
                            <option value="cartao">Cartão</option>
                            <option value="boleto">Boleto</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nome do pagador</label>
                    <input type="text" name="payer_name" class="form-control" value="{{ old('payer_name', auth()->user()->name) }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>CPF do pagador</label>
                        <input type="text" name="payer_cpf" class="form-control" value="{{ old('payer_cpf', auth()->user()->cpf) }}">
                    </div>

                    <div class="form-group">
                        <label>Parcelas</label>
                        <input type="number" name="installments" class="form-control" min="1" max="12" value="{{ old('installments', 1) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Bandeira do cartão</label>
                        <input type="text" name="card_brand" class="form-control" value="{{ old('card_brand') }}" placeholder="Visa, Master...">
                    </div>

                    <div class="form-group">
                        <label>Últimos 4 dígitos do cartão</label>
                        <input type="text" name="card_last_four" class="form-control" maxlength="4" value="{{ old('card_last_four') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Observações</label>
                    <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Finalizar pedido
                </button>
            </div>
        </form>
    </div>
@endsection