@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2 class="mb-4">Dashboard</h2>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total de Pedidos</h5>
                <p class="card-text display-4">{{ $totalOrders }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Pedidos Pendentes</h5>
                <p class="card-text display-4">{{ $pendingOrders }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total de Produtos</h5>
                <p class="card-text display-4">{{ $totalProducts }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Gráfico de Vendas do Mês Atual -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Vendas do Mês Atual</h5>
    </div>
    <div class="card-body">
        <canvas id="salesChart" height="80"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Pedidos Recentes</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">Ver Todos</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $order->order_status === 'completed' ? 'success' : ($order->order_status === 'pending' ? 'warning' : 'info') }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($salesLabels),
            datasets: [{
                label: 'Vendas (R$)',
                data: @json($salesTotals),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
