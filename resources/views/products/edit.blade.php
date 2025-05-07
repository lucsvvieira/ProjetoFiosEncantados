@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Editar Produto</h1>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nome do Produto</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
                <textarea name="description" id="description" rows="4" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Preço</label>
                <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Estoque</label>
                <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock) }}" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('stock') border-red-500 @enderror">
                @error('stock')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="active" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="active" id="active" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('active') border-red-500 @enderror">
                    <option value="1" {{ old('active', $product->active) ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ old('active', $product->active) ? '' : 'selected' }}>Inativo</option>
                </select>
                @error('active')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Imagem do Produto</label>
                @if($product->image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded">
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('image') border-red-500 @enderror">
                <p class="text-gray-600 text-xs mt-1">Deixe em branco para manter a imagem atual</p>
                @error('image')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Atualizar Produto
                </button>
                <a href="{{ route('products.index') }}" class="text-blue-500 hover:text-blue-700">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection 