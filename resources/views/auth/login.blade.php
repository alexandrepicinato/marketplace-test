@extends('layouts.app')

@section('title', 'Entrar')

@section('content')
    <div class="auth-wrapper">
        <div class="card">
            <div class="page-header" style="margin-bottom: 20px;">
                <div>
                    <h1>Entrar</h1>
                    <p>Acesse sua conta para comprar, vender ou se afiliar.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <label class="checkbox">
                        <input type="checkbox" name="remember" value="1">
                        Lembrar de mim
                    </label>

                    <button type="submit" class="btn btn-primary">
                        Entrar
                    </button>

                    <p class="muted" style="text-align: center;">
                        Não tem conta?
                        <a href="{{ route('register') }}"><strong>Criar conta</strong></a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection