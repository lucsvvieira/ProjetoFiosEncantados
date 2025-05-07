@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-accent">Gerenciar Produtos</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-secondary text-primary px-6 py-3 rounded-lg hover:bg-secondary-light transition duration-300 shadow-lg shadow-secondary/20 flex items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            Novo Produto
        </a>
    </div>

    @if(session('success'))
        <div class="bg-secondary/10 border border-secondary/20 text-secondary px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-dark">
                        <th class="px-6 py-4 text-left text-accent font-semibold">Imagem</th>
                        <th class="px-6 py-4 text-left text-accent font-semibold">Nome</th>
                        <th class="px-6 py-4 text-left text-accent font-semibold">Preço</th>
                        <th class="px-6 py-4 text-left text-accent font-semibold">Estoque</th>
                        <th class="px-6 py-4 text-left text-accent font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-accent font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary/20">
                    @foreach($products as $product)
                        <tr class="hover:bg-dark/50 transition duration-300">
                            <td class="px-6 py-4">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 bg-secondary/10 flex items-center justify-center rounded-lg">
                                        <i class="bi bi-image text-2xl text-secondary"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <h3 class="font-semibold text-accent">{{ $product->name }}</h3>
                                    <p class="text-accent/60 text-sm">{{ Str::limit($product->description, 50) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-secondary font-semibold">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-accent">{{ $product->stock }} unidades</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500/20 text-green-500">
                                        <i class="bi bi-check-circle-fill mr-2"></i>
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-500/20 text-red-500">
                                        <i class="bi bi-x-circle-fill mr-2"></i>
                                        Inativo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-accent hover:text-secondary transition duration-300">
                                        <i class="bi bi-pencil text-xl"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-accent hover:text-red-500 transition duration-300">
                                            <i class="bi bi-trash text-xl"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
