@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary via-dark to-dark-light text-accent py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Artesanato com Amor e Dedicação</h1>
                <p class="text-xl mb-8 text-accent/90">Descubra peças únicas feitas à mão com muito carinho para decorar sua casa.</p>
                <a href="/produtos" class="bg-secondary text-primary px-8 py-3 rounded-lg font-semibold hover:bg-secondary-light transition duration-300 shadow-lg shadow-secondary/20">
                    Ver Produtos
                </a>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-dark to-transparent"></div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 bg-dark rounded-lg border border-secondary/20 hover:border-secondary/40 transition duration-300">
                    <div class="flex justify-center mb-4">
                        <i class="bi bi-heart-fill text-5xl text-secondary"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-accent">Feito com Amor</h3>
                    <p class="text-accent/80">Cada peça é criada com muito carinho e dedicação.</p>
                </div>
                <div class="text-center p-6 bg-dark rounded-lg border border-secondary/20 hover:border-secondary/40 transition duration-300">
                    <div class="flex justify-center mb-4">
                        <i class="bi bi-star-fill text-5xl text-secondary"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-accent">Qualidade Premium</h3>
                    <p class="text-accent/80">Materiais selecionados para garantir a melhor qualidade.</p>
                </div>
                <div class="text-center p-6 bg-dark rounded-lg border border-secondary/20 hover:border-secondary/40 transition duration-300">
                    <div class="flex justify-center mb-4">
                        <i class="bi bi-truck text-5xl text-secondary"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-accent">Entrega Rápida</h3>
                    <p class="text-accent/80">Seus produtos chegam com segurança e rapidez.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-dark">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-accent">Nossas Categorias</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="/produtos" class="group">
                    <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 hover:border-secondary/40 transition duration-300">
                        <div class="h-48 bg-secondary/10 flex items-center justify-center">
                            <img src="{{ asset('storage/images/handbag.png') }}" alt="Decoração" class="h-32 w-32 object-contain">
                        </div>
                        <div class="p-4 text-center">
                            <h3 class="font-semibold text-lg text-accent">Decoração</h3>
                            <p class="text-accent/80 mt-2">Peças únicas para transformar seu ambiente.</p>
                        </div>
                    </div>
                </a>
                <a href="/produtos" class="group">
                    <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 hover:border-secondary/40 transition duration-300">
                        <div class="h-48 bg-secondary/10 flex items-center justify-center">
                            <img src="{{ asset('storage/images/giftbox.png') }}" alt="Presentes" class="h-32 w-32 object-contain">
                        </div>
                        <div class="p-4 text-center">
                            <h3 class="font-semibold text-lg text-accent">Presentes</h3>
                            <p class="text-accent/80 mt-2">Surpreenda quem você ama com algo especial.</p>
                        </div>
                    </div>
                </a>
                <a href="/produtos" class="group">
                    <div class="bg-dark-light rounded-lg shadow-lg overflow-hidden border border-secondary/20 hover:border-secondary/40 transition duration-300">
                        <div class="h-48 bg-secondary/10 flex items-center justify-center">
                            <img src="{{ asset('storage/images/knitting.png') }}" alt="Artesanato" class="h-32 w-32 object-contain">
                        </div>
                        <div class="p-4 text-center">
                            <h3 class="font-semibold text-lg text-accent">Artesanato</h3>
                            <p class="text-accent/80 mt-2">Produtos feitos à mão com carinho e criatividade.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-dark-light">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4 text-accent">Fique por dentro das novidades!</h2>
            <p class="text-accent/80 mb-8">Receba em primeira mão informações sobre novos produtos e promoções.</p>
            <form class="max-w-md mx-auto">
                <div class="flex gap-4">
                    <input type="email" placeholder="Seu melhor e-mail" class="flex-1 px-4 py-2 bg-dark border border-secondary/20 rounded-lg focus:outline-none focus:border-secondary text-accent placeholder-accent/50">
                    <button type="submit" class="bg-secondary text-primary px-6 py-2 rounded-lg hover:bg-secondary-light transition duration-300 shadow-lg shadow-secondary/20">
                        Inscrever-se
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection 