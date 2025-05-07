@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-accent">Finalizar Compra</h1>

    @if(session()->has('cart') && is_array(session('cart')) && count(session('cart')) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 p-6">
                    <h2 class="text-xl font-semibold mb-6 text-accent">Informações de Entrega</h2>
                    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-accent mb-2">Nome Completo</label>
                                <input type="text" id="name" name="name" required
                                    class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                            </div>
                            <div>
                                <label for="email" class="block text-accent mb-2">E-mail</label>
                                <input type="email" id="email" name="email" required
                                    class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                            </div>
                            <div>
                                <label for="phone" class="block text-accent mb-2">Telefone</label>
                                <input type="tel" id="phone" name="phone" required
                                    class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                            </div>
                            <div>
                                <label for="cep" class="block text-accent mb-2">CEP</label>
                                <input type="text" id="cep" name="cep" required
                                    class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                            </div>
                            <div class="md:col-span-2">
                                <label for="address" class="block text-accent mb-2">Endereço</label>
                                <input type="text" id="address" name="address" required
                                    class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                            </div>
                            <div>
                                <label for="number" class="block text-accent mb-2">Número</label>
                                <input type="text" id="number" name="number" required
                                    class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                            </div>
                            <div>
                                <label for="complement" class="block text-accent mb-2">Complemento</label>
                                <input type="text" id="complement" name="complement"
                                    class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                            </div>
                            <div>
                                <label for="neighborhood" class="block text-accent mb-2">Bairro</label>
                                <input type="text" id="neighborhood" name="neighborhood" required
                                    class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                            </div>
                            <div>
                                <label for="city" class="block text-accent mb-2">Cidade</label>
                                <input type="text" id="city" name="city" required
                                    class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                            </div>
                            <div>
                                <label for="state" class="block text-accent mb-2">Estado</label>
                                <input type="text" id="state" name="state" required
                                    class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                            </div>
                        </div>

                        <div class="border-t border-secondary/20 my-6"></div>

                        <h2 class="text-xl font-semibold mb-6 text-accent">Forma de Pagamento</h2>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="radio" id="pix" name="payment_method" value="pix" class="text-secondary focus:ring-secondary" checked>
                                <label for="pix" class="ml-2 text-accent">PIX</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="credit_card" name="payment_method" value="credit_card" class="text-secondary focus:ring-secondary">
                                <label for="credit_card" class="ml-2 text-accent">Cartão de Crédito</label>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-secondary text-primary text-center px-6 py-3 rounded-lg hover:bg-secondary-light transition duration-300 shadow-lg shadow-secondary/20">
                            Finalizar Pedido
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 p-6">
                    <h2 class="text-xl font-semibold mb-4 text-accent">Resumo do Pedido</h2>
                    <div class="space-y-4 mb-6">
                        @foreach(session('cart') as $id => $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 flex-shrink-0">
                                        @if($item['image'])
                                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <div class="w-full h-full bg-secondary/10 flex items-center justify-center rounded-lg">
                                                <i class="bi bi-image text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-accent">{{ $item['name'] }}</h3>
                                        <p class="text-sm text-accent/80">Qtd: {{ $item['quantity'] }}</p>
                                    </div>
                                </div>
                                <p class="text-sm font-bold text-secondary">R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-accent/80">
                            <span>Subtotal</span>
                            <span>R$ {{ number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, session('cart'))), 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-accent/80">
                            <span>Frete</span>
                            <span>R$ {{ number_format(10, 2, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-secondary/20 my-2"></div>
                        <div class="flex justify-between text-accent font-semibold">
                            <span>Total</span>
                            <span>R$ {{ number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, session('cart'))) + 10, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <h2 class="text-2xl font-semibold mb-4 text-accent">Seu carrinho está vazio</h2>
            <p class="text-accent/80 mb-8">Adicione alguns produtos para começar suas compras.</p>
            <a href="{{ route('products.index') }}" class="bg-secondary text-primary px-6 py-3 rounded-lg hover:bg-secondary-light transition duration-300 shadow-lg shadow-secondary/20">
                Ver Produtos
            </a>
        </div>
    @endif
</div>
@endsection 