@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto text-center">
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="text-6xl text-pink-primary mb-4">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h1 class="text-3xl font-bold text-pink-primary mb-4">Pedido Realizado com Sucesso!</h1>
        <p class="text-gray-600 mb-6">Obrigado por comprar conosco. Seu pedido foi registrado e está sendo processado.</p>
        
        <div class="bg-pink-lighter rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-pink-primary mb-4">Detalhes do Pedido</h2>
            <div class="text-left">
                <p class="mb-2"><strong>Número do Pedido:</strong> #{{ $order->id }}</p>
                <p class="mb-2"><strong>Cliente:</strong> {{ $order->customer_name }}</p>
                <p class="mb-2"><strong>E-mail:</strong> {{ $order->customer_email }}</p>
                <p class="mb-2"><strong>Total:</strong> R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                <p class="mb-2"><strong>Forma de Pagamento:</strong> 
                    @switch($order->payment_method)
                        @case('credit_card')
                            Cartão de Crédito
                            @break
                        @case('pix')
                            PIX
                            @break
                        @case('boleto')
                            Boleto Bancário
                            @break
                    @endswitch
                </p>
            </div>
        </div>

        @if($order->payment_method === 'pix')
            <div class="bg-pink-lighter rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-pink-primary mb-4">Instruções para Pagamento PIX</h2>
                <p class="text-gray-600 mb-4">Para finalizar seu pedido, realize o pagamento via PIX usando os dados abaixo:</p>
                <div class="text-left">
                    <p class="mb-2"><strong>Chave PIX:</strong> {{ session('pix_data.key') }}</p>
                    <p class="mb-2"><strong>Valor:</strong> R$ {{ number_format(session('pix_data.amount'), 2, ',', '.') }}</p>
                    <p class="mb-2"><strong>Descrição:</strong> {{ session('pix_data.description') }}</p>
                </div>
            </div>
        @endif

        <div class="space-y-4">
            <a href="{{ route('home') }}" class="inline-block bg-pink-primary hover:bg-pink-light text-white font-semibold py-3 px-6 rounded-lg transition">
                <i class="bi bi-house-door"></i> Voltar para a Loja
            </a>
        </div>
    </div>
</div>
@endsection
