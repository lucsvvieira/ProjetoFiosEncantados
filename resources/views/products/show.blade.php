@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
            <div>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover rounded-lg">
                @else
                    <div class="w-full h-96 bg-secondary/10 flex items-center justify-center rounded-lg">
                        <i class="bi bi-image text-6xl text-secondary"></i>
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-4 text-accent">{{ $product->name }}</h1>
                <p class="text-2xl font-bold mb-6 text-secondary">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                <p class="text-accent/80 mb-6">{{ $product->description }}</p>
                
                <div class="mb-6">
                    @if($product->active && $product->stock > 0)
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500/20 text-green-500">
                            <i class="bi bi-check-circle-fill mr-2"></i>
                            Em estoque
                        </div>
                    @else
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-500/20 text-red-500">
                            <i class="bi bi-x-circle-fill mr-2"></i>
                            Indisponível
                        </div>
                    @endif
                </div>

                @if($product->active && $product->stock > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex gap-4 items-center">
                        @csrf
                        <div class="flex items-center">
                            <label for="quantity" class="sr-only">Quantidade</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                class="w-20 px-3 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent">
                        </div>
                        <button type="submit" class="bg-secondary text-primary px-6 py-2 rounded-lg hover:bg-secondary-light transition duration-300 shadow-lg shadow-secondary/20">
                            Adicionar ao Carrinho
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    console.log('Cart script start');
    
    // Teste se o formulário existe
    const form = document.getElementById('addToCartForm');
    if (form) {
        console.log('Form found:', {
            action: form.action,
            method: form.method
        });
    } else {
        console.log('Form not found');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('addToCartForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form submitted');
                
                const formData = new FormData(form);
                console.log('Form data:', Object.fromEntries(formData));

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        // Atualiza o contador do carrinho
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount) {
                            cartCount.textContent = data.cartCount;
                        }
                        showNotification('Produto adicionado ao carrinho!', 'success');
                    } else {
                        showNotification(data.message || 'Erro ao adicionar produto ao carrinho', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Erro ao adicionar produto ao carrinho', 'error');
                });
            });
        }
    });

    function showNotification(message, type = 'success') {
        // Remove notificações anteriores
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());

        // Cria nova notificação
        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 flex items-center space-x-2 ${
            type === 'success' ? 'bg-secondary text-primary' : 'bg-red-500 text-white'
        }`;
        
        // Adiciona ícone
        const icon = document.createElement('i');
        icon.className = type === 'success' ? 'bi bi-check-circle' : 'bi bi-exclamation-circle';
        notification.appendChild(icon);

        // Adiciona mensagem
        const messageSpan = document.createElement('span');
        messageSpan.textContent = message;
        notification.appendChild(messageSpan);

        // Adiciona ao DOM
        document.body.appendChild(notification);

        // Anima entrada
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        notification.style.transition = 'all 0.3s ease-out';

        // Força reflow
        notification.offsetHeight;

        // Anima para posição final
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';

        // Remove após 3 segundos
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    console.log('Cart script end');
</script>
@endpush
