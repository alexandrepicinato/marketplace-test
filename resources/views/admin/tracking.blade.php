@extends('layouts.app')

@section('title', 'Rastreio do Pedido')

@section('content')
    <div class="page-header">
        <div>
            <h1>Rastreio do Pedido #{{ $order->id }}</h1>
            <p>Somente administradores podem atualizar o rastreio.</p>
        </div>

        <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Resumo</h2>

        <p><strong>Produto:</strong> {{ $order->product->name ?? '-' }}</p>
        <p><strong>Comprador:</strong> {{ $order->buyer->name ?? '-' }}</p>
        <p><strong>Status do pedido:</strong> {{ $order->status }}</p>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <h2 style="margin-top: 0;">Atualizar rastreio</h2>

        <form method="POST" action="{{ route('admin.orders.tracking.update', $order) }}">
            @csrf

            <div class="form-grid">
                <div class="form-row">
                    <div class="form-group">
                        <label>Código de rastreio</label>
                        <input type="text" name="tracking_code" class="form-control" value="{{ old('tracking_code', $order->tracking_code) }}">
                    </div>

                    <div class="form-group">
                        <label>Transportadora</label>
                        <input type="text" name="tracking_carrier" class="form-control" value="{{ old('tracking_carrier', $order->tracking_carrier) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Status do rastreio</label>
                    <input type="text" name="tracking_status" class="form-control" value="{{ old('tracking_status', $order->tracking_status) }}" placeholder="Ex: Pedido enviado, Em rota, Entregue" required>
                </div>

                <div class="form-group">
                    <label>URL de rastreio</label>
                    <input type="url" name="tracking_url" class="form-control" value="{{ old('tracking_url', $order->tracking_url) }}">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Localização</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Ex: Curitiba/PR">
                    </div>

                    <div class="form-group">
                        <label>Data do evento</label>
                        <input type="datetime-local" name="event_at" class="form-control" value="{{ old('event_at') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Salvar rastreio
                </button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 style="margin-top: 0;">Histórico de rastreio</h2>

        @if ($order->trackingEvents->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Local</th>
                        <th>Admin</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($order->trackingEvents->sortByDesc('created_at') as $event)
                        <tr>
                            <td>{{ optional($event->event_at)->format('d/m/Y H:i') ?? $event->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $event->status }}</td>
                            <td>{{ $event->location ?? '-' }}</td>
                            <td>{{ $event->admin->name ?? '-' }}</td>
                            <td>{{ $event->description ?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="muted">Nenhum evento de rastreio registrado.</p>
        @endif
    </div>
@endsection