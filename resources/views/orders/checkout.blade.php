@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-pink-primary mb-6">Finalizar Compra</h1>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-bold text-pink-primary mb-4">Informações do Cliente</h2>
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-semibold mb-1">Nome Completo</label>
                        <input type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-primary @error('customer_name') border-red-500 @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="customer_email" class="block text-sm font-semibold mb-1">E-mail</label>
                        <input type="email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-primary @error('customer_email') border-red-500 @enderror" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                        @error('customer_email')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="customer_phone" class="block text-sm font-semibold mb-1">Telefone</label>
                        <input type="tel" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-primary @error('customer_phone') border-red-500 @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                        @error('customer_phone')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="cep" class="block text-sm font-semibold mb-1">CEP</label>
                        <div class="flex gap-2">
                            <input type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-primary @error('cep') border-red-500 @enderror" id="cep" name="cep" value="{{ old('cep') }}" required>
                            <button type="button" id="calculate-shipping" class="bg-pink-primary hover:bg-pink-light text-white px-4 py-2 rounded transition">Calcular Frete</button>
                        </div>
                        @error('cep')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div id="shipping-info" class="mb-4 hidden">
                    <div class="bg-pink-lighter text-pink-primary rounded p-3">
                        <div id="shipping-address"></div>
                        <div id="shipping-cost" class="mt-2"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="shipping_address" class="block text-sm font-semibold mb-1">Endereço de Entrega</label>
                    <textarea class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-primary @error('shipping_address') border-red-500 @enderror" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <h2 class="text-xl font-bold text-pink-primary mb-4">Forma de Pagamento</h2>
                <div class="mb-4">
                    <div class="flex flex-col gap-2">
                        <label class="inline-flex items-center">
                            <input class="form-radio text-pink-primary" type="radio" name="payment_method" id="payment_credit_card" value="credit_card" {{ old('payment_method') == 'credit_card' ? 'checked' : '' }}>
                            <span class="ml-2"><i class="bi bi-credit-card"></i> Cartão de Crédito</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input class="form-radio text-pink-primary" type="radio" name="payment_method" id="payment_pix" value="pix" {{ old('payment_method') == 'pix' ? 'checked' : '' }} required>
                            <span class="ml-2"><i class="bi bi-qr-code"></i> PIX</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input class="form-radio text-pink-primary" type="radio" name="payment_method" id="payment_boleto" value="boleto" {{ old('payment_method') == 'boleto' ? 'checked' : '' }}>
                            <span class="ml-2"><i class="bi bi-upc"></i> Boleto Bancário</span>
                        </label>
                    </div>
                    @error('payment_method')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div id="credit-card-fields" class="mb-4 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="card_holder_name" class="block text-sm font-semibold mb-1">Nome no Cartão</label>
                            <input type="text" name="card_holder_name" id="card_holder_name" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-primary" placeholder="Como está escrito no cartão">
                        </div>
                        <div>
                            <label for="card_number" class="block text-sm font-semibold mb-1">Número do Cartão</label>
                            <input type="text" name="card_number" id="card_number" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-primary" placeholder="0000 0000 0000 0000" maxlength="16">
                        </div>
                        <div>
                            <label for="card_expiry" class="block text-sm font-semibold mb-1">Validade</label>
                            <input type="text" name="card_expiry" id="card_expiry" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-primary" placeholder="MM/AA" maxlength="5">
                        </div>
                        <div>
                            <label for="card_cvv" class="block text-sm font-semibold mb-1">CVV</label>
                            <input type="text" name="card_cvv" id="card_cvv" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-primary" placeholder="000" maxlength="3">
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="w-full bg-pink-primary hover:bg-pink-light text-white font-semibold py-3 rounded text-lg transition">
                        <i class="bi bi-lock"></i> Finalizar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-pink-primary mb-4">Resumo do Pedido</h2>
            @foreach($cart as $item)
                <div class="flex justify-between mb-3">
                    <div>
                        <span>{{ $item['name'] }}</span>
                        <br>
                        <small class="text-gray-500">Qtd: {{ $item['quantity'] }}</small>
                    </div>
                    <span>R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</span>
                </div>
            @endforeach
            <hr class="my-4">
            <div class="flex justify-between mb-2">
                <span>Subtotal</span>
                <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong>
            </div>
            <div class="flex justify-between mb-2">
                <span>Frete</span>
                <strong id="shipping-cost-summary">Calcular</strong>
            </div>
            <div class="flex justify-between mb-2">
                <span>Total</span>
                <strong class="text-pink-primary text-xl" id="total-with-shipping" data-total="{{ $total }}">R$ {{ number_format($total, 2, ',', '.') }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="{{ asset('js/checkout.js') }}"></script>
<script>
$(document).ready(function() {
    $('#customer_phone').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');
});
</script>
@endpush
