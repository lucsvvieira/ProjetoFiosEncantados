@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-accent">Nossos Produtos</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 hover:border-secondary/40 transition duration-300">
                <div class="relative">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-secondary/10 flex items-center justify-center">
                            <i class="bi bi-image text-4xl text-secondary"></i>
                        </div>
                    @endif
                    @if(!$product->active)
                        <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                            Indisponível
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2 text-accent">{{ $product->name }}</h2>
                    <p class="text-accent/80 mb-4 line-clamp-2">{{ $product->description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-secondary">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                        <div class="flex gap-2">
                            <a href="{{ route('products.show', $product) }}" class="text-accent hover:text-secondary transition duration-300">
                                <i class="bi bi-eye text-xl"></i>
                            </a>
                            @if($product->active && $product->stock > 0)
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-accent hover:text-secondary transition duration-300">
                                        <i class="bi bi-cart-plus text-xl"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>

@push('styles')
<style>
    .pagination {
        @apply flex items-center space-x-2;
    }
    .pagination > * {
        @apply px-4 py-2 rounded-md transition-colors duration-200;
    }
    .pagination > *:hover {
        @apply bg-pink-lighter;
    }
    .pagination .active {
        @apply bg-pink-primary text-white;
    }
    .pagination .disabled {
        @apply opacity-50 cursor-not-allowed;
    }
    .pagination .disabled:hover {
        @apply bg-transparent;
    }
</style>
@endpush
@endsection
