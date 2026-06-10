@extends('layouts.app')

@section('title', 'Login Administrativo')

@section('content')
    <div class="auth-wrapper">
        <div class="card">
            <div class="page-header" style="margin-bottom: 20px;">
                <div>
                    <h1>Admin</h1>
                    <p>Acesso restrito ao painel administrativo.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label>E-mail</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label>Senha</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Entrar no admin
                    </button>

                    <a href="{{ route('home') }}" class="btn btn-secondary">
                        Voltar para loja
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection