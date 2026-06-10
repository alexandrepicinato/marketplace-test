@extends('layouts.app')

@section('title', 'Admin - Usuários')

@section('content')
    <div class="page-header">
        <div>
            <h1>Usuários</h1>
            <p>Lista de usuários cadastrados no sistema.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div class="card">
        @if ($users->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>CPF</th>
                        <th>RG</th>
                        <th>Admin</th>
                        <th>Produtos</th>
                        <th>Compras</th>
                        <th>Vendas</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->cpf ?? '-' }}</td>
                            <td>{{ $user->rg ?? '-' }}</td>
                            <td>
                                @if ($user->is_admin)
                                    <span class="badge badge-active">Sim</span>
                                @else
                                    <span class="badge badge-inactive">Não</span>
                                @endif
                            </td>
                            <td>{{ $user->products_count }}</td>
                            <td>{{ $user->orders_as_buyer_count }}</td>
                            <td>{{ $user->orders_as_seller_count }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="muted">Nenhum usuário encontrado.</p>
        @endif
    </div>
@endsection