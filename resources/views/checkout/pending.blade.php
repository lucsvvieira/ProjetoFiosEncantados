@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 p-8">
            <div class="mb-6">
                <div class="w-20 h-20 bg-secondary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-clock text-4xl text-secondary"></i>
                </div>
                <h1 class="text-3xl font-bold mb-4 text-accent">Pagamento Pendente</h1>
                <p class="text-accent/80 mb-8">Seu pedido foi recebido e está aguardando a confirmação do pagamento.</p>
            </div>

            <div class="space-y-4 mb-8">
                <div class="border-t border-secondary/20 pt-4">
                    <h2 class="text-xl font-semibold mb-4 text-accent">O que acontece agora?</h2>
                    <ol class="list-decimal list-inside space-y-2 text-accent/80">
                        <li>O Mercado Pago está processando seu pagamento</li>
                        <li>Você receberá um e-mail quando o pagamento for confirmado</li>
                        <li>Após a confirmação, seu pedido será processado e preparado para envio</li>
                    </ol>
                </div>
            </div>

            <div class="space-y-4">
                <a href="{{ route('home') }}" class="inline-block bg-secondary text-primary px-6 py-3 rounded-lg hover:bg-secondary-light transition duration-300 shadow-lg shadow-secondary/20">
                    Voltar para a Loja
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 