@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-accent">Seu Carrinho</h1>

    @if(session()->has('cart') && is_array(session('cart')) && count(session('cart')) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                @foreach(session('cart') as $id => $item)
                    <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 mb-4">
                        <div class="p-4 flex items-center gap-4">
                            <div class="w-24 h-24 flex-shrink-0">
                                @if($item['image'])
                                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <div class="w-full h-full bg-secondary/10 flex items-center justify-center rounded-lg">
                                        <i class="bi bi-image text-2xl text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h3 class="text-xl font-semibold text-accent">{{ $item['name'] }}</h3>
                                <p class="text-2xl font-bold text-secondary mt-2">R$ {{ number_format($item['price'], 2, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                        class="w-20 px-3 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                                    <button type="submit" class="text-accent hover:text-secondary transition duration-300">
                                        <i class="bi bi-arrow-clockwise text-xl"></i>
                                    </button>
                                </form>
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-accent hover:text-red-500 transition duration-300">
                                        <i class="bi bi-trash text-xl"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="lg:col-span-1">
                <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 p-6">
                    <h2 class="text-xl font-semibold mb-4 text-accent">Resumo do Pedido</h2>
                    <div class="space-y-2 mb-4">
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
                    <a href="{{ route('checkout') }}" class="block w-full bg-secondary text-primary text-center px-6 py-3 rounded-lg hover:bg-secondary-light transition duration-300 shadow-lg shadow-secondary/20">
                        Finalizar Compra
                    </a>
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
