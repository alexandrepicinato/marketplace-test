@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p>Bem-vindo, {{ auth()->user()->name }}.</p>
        </div>

        <a href="{{ route('products.create') }}" class="btn btn-primary">
            Novo Produto
        </a>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Gestão</h2>

        <div class="actions">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Meus produtos</a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Cadastrar produto</a>
            <a href="{{ route('affiliates.index') }}" class="btn btn-secondary">Afiliar-se</a>
            <a href="{{ route('affiliates.my') }}" class="btn btn-secondary">Minhas afiliações</a>
        </div>
    </div>

    <div class="page-header">
        <div>
            <h1>Produtos do marketplace</h1>
            <p>Produtos ativos disponíveis para compra.</p>
        </div>
    </div>

    @include('partials.product-grid', ['products' => $products])
@endsection