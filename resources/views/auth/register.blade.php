@extends('layouts.app')

@section('title', 'Cadastro')

@section('content')
    <div class="auth-wrapper">
        <div class="card">
            <div class="page-header" style="margin-bottom: 20px;">
                <div>
                    <h1>Criar conta</h1>
                    <p>Cadastro rápido. CPF, RG, telefone e endereço serão pedidos no checkout.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Confirmar senha</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Criar conta
                    </button>

                    <p class="muted" style="text-align: center;">
                        Já tem conta?
                        <a href="{{ route('login') }}"><strong>Entrar</strong></a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection