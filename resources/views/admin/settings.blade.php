@extends('layouts.app')

@section('title', 'Configurações')

@section('content')
    <div class="page-header">
        <div>
            <h1>Configurações</h1>
            <p>Somente administradores podem alterar essas opções.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <div class="form-grid">
                <label class="checkbox">
                    <input
                        type="checkbox"
                        name="require_product_approval"
                        value="1"
                        {{ $settings['require_product_approval'] ? 'checked' : '' }}
                    >
                    Exigir autorização administrativa para postar produtos
                </label>

                <label class="checkbox">
                    <input
                        type="checkbox"
                        name="marketplace_enabled"
                        value="1"
                        {{ $settings['marketplace_enabled'] ? 'checked' : '' }}
                    >
                    Marketplace ativo
                </label>

                <label class="checkbox">
                    <input
                        type="checkbox"
                        name="checkout_enabled"
                        value="1"
                        {{ $settings['checkout_enabled'] ? 'checked' : '' }}
                    >
                    Checkout ativo
                </label>

                <label class="checkbox">
                    <input
                        type="checkbox"
                        name="affiliation_enabled"
                        value="1"
                        {{ $settings['affiliation_enabled'] ? 'checked' : '' }}
                    >
                    Sistema de afiliados ativo
                </label>

                <button type="submit" class="btn btn-primary">
                    Salvar configurações
                </button>
            </div>
        </form>
    </div>
@endsection