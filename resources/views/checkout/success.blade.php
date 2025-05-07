@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 p-8">
            <div class="mb-6">
                <div class="w-20 h-20 bg-secondary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-check-circle text-4xl text-secondary"></i>
                </div>
                <h1 class="text-3xl font-bold mb-4 text-accent">Pedido Confirmado!</h1>
                <p class="text-accent/80 mb-8">Obrigado por comprar conosco. Seu pedido foi recebido e está sendo processado.</p>
            </div>

            <div class="space-y-4 mb-8">
                <div class="border-t border-secondary/20 pt-4">
                    <h2 class="text-xl font-semibold mb-4 text-accent">Próximos Passos</h2>
                    <ol class="list-decimal list-inside space-y-2 text-accent/80">
                        <li>Você receberá um e-mail com a confirmação do pedido</li>
                        <li>Seu pedido será processado e preparado para envio</li>
                        <li>Você receberá atualizações sobre o status do seu pedido</li>
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