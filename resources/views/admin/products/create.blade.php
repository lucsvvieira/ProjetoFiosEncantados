@extends('admin.layouts.app')

@section('title', 'Novo Produto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Novo Produto</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do Produto</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="price" class="form-label">Preço</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" 
                                   name="price" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('price') }}" 
                                   required>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Estoque</label>
                        <input type="number" 
                               class="form-control @error('stock') is-invalid @enderror" 
                               id="stock" 
                               name="stock" 
                               min="0" 
                               value="{{ old('stock', 0) }}" 
                               required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Imagem</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Tamanho máximo: 2MB. Formatos: JPG, PNG</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="active" 
                                   name="active" 
                                   value="1" 
                                   {{ old('active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">
                                Produto Ativo
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Salvar Produto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
