@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-accent">{{ isset($product) ? 'Editar Produto' : 'Novo Produto' }}</h1>

    <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20">
        <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-accent font-medium mb-2">Nome do Produto</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" 
                        class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-accent font-medium mb-2">Descrição</label>
                    <textarea name="description" id="description" rows="4" 
                        class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent @error('description') border-red-500 @enderror">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-accent font-medium mb-2">Preço</label>
                        <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $product->price ?? '') }}" 
                            class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-accent font-medium mb-2">Estoque</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock ?? '') }}" 
                            class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent @error('stock') border-red-500 @enderror">
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-accent font-medium mb-2">Imagem do Produto</label>
                    @if(isset($product) && $product->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/*" 
                        class="w-full px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="active" id="active" value="1" {{ old('active', $product->active ?? true) ? 'checked' : '' }} 
                        class="w-4 h-4 bg-dark border-secondary/20 rounded focus:ring-secondary text-secondary">
                    <label for="active" class="ml-2 text-accent">Produto Ativo</label>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-8">
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-secondary/20 rounded-lg text-accent hover:bg-dark transition duration-300">
                    Cancelar
                </a>
                <button type="submit" class="bg-secondary text-primary px-6 py-2 rounded-lg hover:bg-secondary-light transition duration-300 shadow-lg shadow-secondary/20">
                    {{ isset($product) ? 'Atualizar Produto' : 'Criar Produto' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 