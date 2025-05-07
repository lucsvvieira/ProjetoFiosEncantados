@extends('admin.layouts.app')

@section('title', 'Pedidos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pedidos</h2>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Forma de Pagamento</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>
                            <strong>{{ $order->customer_name }}</strong><br>
                            <small>{{ $order->customer_email }}</small>
                        </td>
                        <td>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                        <td>
                            @if($order->payment_method === 'credit_card')
                                <i class="bi bi-credit-card"></i> Cartão
                            @else
                                <i class="bi bi-qr-code"></i> PIX
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->order_status === 'completed' ? 'success' : ($order->order_status === 'pending' ? 'warning' : 'info') }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
