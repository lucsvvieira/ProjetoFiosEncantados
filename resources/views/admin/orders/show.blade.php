@extends('admin.layouts.app')

@section('title', "Pedido #{$order->id}")

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pedido #{{ $order->id }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Itens do Pedido</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Dados do Cliente</h5>
            </div>
            <div class="card-body">
                <p><strong>Nome:</strong> {{ $order->customer_name }}</p>
                <p><strong>E-mail:</strong> {{ $order->customer_email }}</p>
                <p><strong>Telefone:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Endereço:</strong> {{ $order->shipping_address }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Pagamento</h5>
            </div>
            <div class="card-body">
                <p><strong>Método:</strong> 
                    @if($order->payment_method === 'credit_card')
                        <i class="bi bi-credit-card"></i> Cartão de Crédito
                        @if($order->card_number)
                            <br>
                            <small>Final: {{ substr($order->card_number, -4) }}</small>
                        @endif
                    @else
                        <i class="bi bi-qr-code"></i> PIX
                    @endif
                </p>
                <p><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Status do Pedido</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <select name="order_status" class="form-select" onchange="this.form.submit()">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Em Processamento</option>
                            <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Concluído</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                </form>

                <p class="mb-0">
                    <small>Criado em: {{ $order->created_at->format('d/m/Y H:i') }}</small><br>
                    <small>Atualizado em: {{ $order->updated_at->format('d/m/Y H:i') }}</small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
