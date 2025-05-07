<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Loja Joana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#000000',    // Preto
                        'secondary': '#B8860B',  // Dourado escuro
                        'accent': '#FFFFFF',     // Branco
                        'primary-light': '#1a1a1a',
                        'secondary-light': '#DAA520', // Dourado mais claro
                        'accent-light': '#f3f4f6',
                        'dark': '#000000',       // Preto
                        'dark-light': '#1a1a1a', // Preto suave
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-dark min-h-screen flex flex-col">
    <nav class="bg-primary shadow-lg border-b border-secondary/20">
        <div class="container mx-auto px-4 py-2">
            <div class="flex justify-between items-center">
                <a class="text-accent text-2xl font-bold flex items-center space-x-2 hover:text-secondary transition duration-300" href="{{ route('home') }}">
                    <img src="{{ asset('storage/products/logo.jpg') }}" alt="Loja Joana" class="h-16 w-auto object-contain rounded-full shadow-lg border-2 border-secondary/30 bg-dark p-1">
                </a>
                <div class="hidden md:flex items-center space-x-6">
                    <a class="text-accent hover:text-secondary flex items-center space-x-2 transition duration-300" href="{{ route('home') }}">
                        <i class="bi bi-house-door"></i>
                        <span>Início</span>
                    </a>
                    <a class="text-accent hover:text-secondary flex items-center space-x-2 transition duration-300" href="{{ route('products.index') }}">
                        <i class="bi bi-grid"></i>
                        <span>Produtos</span>
                    </a>
                    <div class="relative group">
                        <a class="text-accent hover:text-secondary flex items-center space-x-2 transition duration-300" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3"></i>
                            <span>Carrinho</span>
                            @if(session()->has('cart') && is_array(session('cart')))
                                <span class="cart-count bg-secondary text-primary px-2 py-1 rounded-full text-sm font-bold">
                                    {{ array_sum(array_column(session('cart'), 'quantity')) }}
                                </span>
                            @endif
                        </a>
                        @if(session()->has('cart') && is_array(session('cart')) && count(session('cart')) > 0)
                            <div class="absolute right-0 mt-2 w-80 bg-dark-light rounded-lg shadow-xl border border-secondary/20 p-4 hidden group-hover:block z-50">
                                <div class="max-h-96 overflow-y-auto">
                                    @foreach(session('cart') as $id => $item)
                                        <div class="flex items-center space-x-4 py-2 border-b border-secondary/10 last:border-b-0">
                                            @if($item['image'])
                                                <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded border border-secondary/20">
                                            @else
                                                <div class="w-16 h-16 bg-secondary/10 rounded flex items-center justify-center border border-secondary/20">
                                                    <i class="bi bi-image text-secondary"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <h4 class="text-sm font-semibold text-accent">{{ $item['name'] }}</h4>
                                                <p class="text-sm text-accent/80">Qtd: {{ $item['quantity'] }}</p>
                                                <p class="text-sm font-bold text-secondary">R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 pt-4 border-t border-secondary/20">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-accent">Total:</span>
                                        <span class="text-lg font-bold text-secondary">
                                            R$ {{ number_format(array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, session('cart'))), 2, ',', '.') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('cart.index') }}" class="block w-full bg-secondary text-primary font-bold py-2 px-4 rounded-lg hover:bg-secondary-light transition duration-300">
                                        Ver Carrinho
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <button class="md:hidden text-accent hover:text-secondary transition duration-300" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8 flex-grow">
        @if(session('success'))
            <div class="bg-secondary/10 border border-secondary text-accent p-4 rounded-lg mb-4 flex items-center space-x-2">
                <i class="bi bi-check-circle text-secondary"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-accent p-4 rounded-lg mb-4 flex items-center space-x-2">
                <i class="bi bi-exclamation-circle text-red-500"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-primary border-t border-secondary/20 text-accent py-6">
        <div class="container mx-auto px-4 text-center">
            <p class="flex items-center justify-center space-x-2">
                <i class="bi bi-heart-fill text-secondary"></i>
                <span>&copy; {{ date('Y') }} Fios Encantados. Todos os direitos reservados.</span>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    @stack('scripts')
</body>
</html>
