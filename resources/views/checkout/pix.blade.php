@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-accent">Pagamento via PIX</h1>

        <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 p-6">
            <div class="text-center mb-8">
                <h2 class="text-xl font-semibold mb-4 text-accent">Escaneie o QR Code</h2>
                <div class="bg-white p-4 rounded-lg inline-block">
                    <!-- Aqui você usaria uma API real de PIX para gerar o QR Code -->
                    <div class="w-64 h-64 bg-gray-200 flex items-center justify-center">
                        <p class="text-gray-500">QR Code PIX</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="border-t border-secondary/20 pt-4">
                    <h3 class="text-lg font-semibold mb-2 text-accent">Instruções</h3>
                    <ol class="list-decimal list-inside space-y-2 text-accent/80">
                        <li>Abra o aplicativo do seu banco</li>
                        <li>Escolha a opção PIX</li>
                        <li>Escaneie o QR Code acima</li>
                        <li>Confirme as informações e finalize o pagamento</li>
                    </ol>
                </div>

                <div class="border-t border-secondary/20 pt-4">
                    <h3 class="text-lg font-semibold mb-2 text-accent">Dados do Pedido</h3>
                    <div class="space-y-2 text-accent/80">
                        <p><strong>Valor Total:</strong> R$ {{ number_format($pixData['amount'], 2, ',', '.') }}</p>
                        <p><strong>Número do Pedido:</strong> {{ $pixData['order_id'] }}</p>
                    </div>
                </div>

                <div class="border-t border-secondary/20 pt-4">
                    <h3 class="text-lg font-semibold mb-2 text-accent">Dados de Entrega</h3>
                    <div class="space-y-2 text-accent/80">
                        <p><strong>Nome:</strong> {{ $customerData['name'] }}</p>
                        <p><strong>Endereço:</strong> {{ $customerData['address'] }}, {{ $customerData['number'] }}</p>
                        @if($customerData['complement'])
                            <p><strong>Complemento:</strong> {{ $customerData['complement'] }}</p>
                        @endif
                        <p><strong>Bairro:</strong> {{ $customerData['neighborhood'] }}</p>
                        <p><strong>Cidade/UF:</strong> {{ $customerData['city'] }}/{{ $customerData['state'] }}</p>
                        <p><strong>CEP:</strong> {{ $customerData['cep'] }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-accent/80 mb-4">Após o pagamento, você receberá um e-mail com a confirmação do pedido.</p>
                <a href="{{ route('home') }}" class="inline-block bg-secondary text-primary px-6 py-3 rounded-lg hover:bg-secondary-light transition duration-300 shadow-lg shadow-secondary/20">
                    Voltar para a Loja
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 