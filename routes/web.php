<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;

Route::middleware('web')->group(function () {
    // Rotas públicas
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Rotas de produtos
    Route::get('/produtos', [ProductController::class, 'index'])->name('products.index');
    Route::get('/produtos/{product}', [ProductController::class, 'show'])->name('products.show');
    
    // Rotas do carrinho
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Rotas de checkout e pedidos
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/checkout/pending', [CheckoutController::class, 'pending'])->name('checkout.pending');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Rotas de cálculo de frete
    Route::post('/shipping/calculate', [ShippingController::class, 'calculate'])->name('shipping.calculate');

    // Rotas do painel administrativo
    Route::prefix('admin')->name('admin.')->group(function () {
        // Rotas públicas do admin
        Route::get('/login', [AdminController::class, 'login'])->name('login');
        Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');
        
        // Rotas protegidas do admin
        Route::middleware([\App\Http\Middleware\AdminAccess::class])->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
            Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

            // Gerenciamento de Pedidos
            Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
            Route::get('/orders/{order}', [AdminController::class, 'orderShow'])->name('orders.show');
            Route::put('/orders/{order}', [AdminController::class, 'orderUpdate'])->name('orders.update');

            // Gerenciamento de Produtos
            Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
            Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
            Route::get('/products/{product}', [ProductController::class, 'show'])->name('admin.products.show');
        });
    });

    Route::get('/teste-sessao', function () {
        $valor = session('teste');
        if (!$valor) {
            session(['teste' => 'funciona']);
            return 'Primeira vez, setando valor na sessão.';
        }
        return 'Valor da sessão: ' . $valor;
    });
});
