@extends('layouts.app')

@section('title', 'Admin - Usuários')

@section('content')
    <div class="page-header">
        <div>
            <h1>Usuários</h1>
            <p>Ative, desative ou suspenda contas de usuários.</p>
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
                        <th>Usuário</th>
                        <th>CPF/RG</th>
                        <th>Status</th>
                        <th>Admin</th>
                        <th>Produtos</th>
                        <th>Compras</th>
                        <th>Vendas</th>
                        <th>Alterar status</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>

                            <td>
                                <strong>{{ $user->name }}</strong>
                                <div class="muted">{{ $user->email }}</div>

                                @if ($user->suspension_reason)
                                    <div class="muted">
                                        Motivo: {{ $user->suspension_reason }}
                                    </div>
                                @endif

                                @if ($user->suspended_until)
                                    <div class="muted">
                                        Suspenso até: {{ $user->suspended_until->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div>CPF: {{ $user->cpf ?? '-' }}</div>
                                <div>RG: {{ $user->rg ?? '-' }}</div>
                            </td>

                            <td>
                                @if ($user->account_status === 'active')
                                    <span class="badge badge-active">Ativo</span>
                                @elseif ($user->account_status === 'inactive')
                                    <span class="badge badge-inactive">Desativado</span>
                                @else
                                    <span class="badge badge-inactive">Suspenso</span>
                                @endif
                            </td>

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

                            <td>
                                @if ($user->id === auth()->id())
                                    <span class="muted">Sua conta</span>
                                @else
                                    <form method="POST" action="{{ route('admin.users.status', $user) }}">
                                        @csrf
                                        @method('PATCH')

                                        <div class="form-grid" style="gap: 8px;">
                                            <select name="account_status" class="form-control">
                                                <option value="active" {{ $user->account_status === 'active' ? 'selected' : '' }}>
                                                    Ativo
                                                </option>

                                                <option value="inactive" {{ $user->account_status === 'inactive' ? 'selected' : '' }}>
                                                    Desativado
                                                </option>

                                                <option value="suspended" {{ $user->account_status === 'suspended' ? 'selected' : '' }}>
                                                    Suspenso
                                                </option>
                                            </select>

                                            <input
                                                type="datetime-local"
                                                name="suspended_until"
                                                class="form-control"
                                                value="{{ $user->suspended_until ? $user->suspended_until->format('Y-m-d\TH:i') : '' }}"
                                            >

                                            <textarea
                                                name="suspension_reason"
                                                class="form-control"
                                                placeholder="Motivo da suspensão/desativação"
                                            >{{ $user->suspension_reason }}</textarea>

                                            <button type="submit" class="btn btn-primary btn-small">
                                                Salvar
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </td>
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