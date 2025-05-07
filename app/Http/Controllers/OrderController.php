<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('orders.checkout', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|min:3|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => ['required', 'regex:/^\(\d{2}\) \d{5}-\d{4}$/'],
            'cep' => ['required', 'regex:/^\d{5}-\d{3}$/'],
            'shipping_address' => 'required|string|min:10|max:500',
            'payment_method' => 'required|in:pix,credit_card,boleto'
        ], [
            'customer_name.required' => 'O nome é obrigatório.',
            'customer_name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'customer_email.required' => 'O e-mail é obrigatório.',
            'customer_email.email' => 'Informe um e-mail válido.',
            'customer_phone.required' => 'O telefone é obrigatório.',
            'customer_phone.regex' => 'Informe um telefone válido no formato (99) 99999-9999.',
            'cep.required' => 'O CEP é obrigatório.',
            'cep.regex' => 'Informe um CEP válido no formato 00000-000.',
            'shipping_address.required' => 'O endereço de entrega é obrigatório.',
            'shipping_address.min' => 'O endereço deve ter pelo menos 10 caracteres.',
            'payment_method.required' => 'Escolha uma forma de pagamento.'
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending'
            ]);

            foreach ($cart as $productId => $item) {
                $product = Product::findOrFail($productId);
                $order->items()->create([
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
            }

            // Processar pagamento de acordo com o método escolhido
            if ($request->payment_method === 'credit_card') {
                $this->processCardPayment($order, $request);
            } else { // pix
                $this->processPixPayment($order);
            }
            
            DB::commit();
            session()->forget('cart');
            
            return redirect()->route('orders.success', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao processar pedido: ' . $e->getMessage());
        }
    }

    private function processCardPayment($order, $request)
    {
        // Validar dados do cartão
        $request->validate([
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|size:16',
            'card_expiry' => 'required|string|size:5', // formato: MM/YY
            'card_cvv' => 'required|string|size:3'
        ]);

        // Salvar dados do cartão (serão criptografados automaticamente pelo trait)
        $order->update([
            'card_holder_name' => $request->card_holder_name,
            'card_number' => $request->card_number,
            'card_expiry' => $request->card_expiry,
            'card_cvv' => $request->card_cvv
        ]);

        // Enviar e-mail para a loja com os dados do pedido
        $this->sendNewOrderEmail($order);
    }

    private function processPixPayment($order)
    {
        // Aqui você pode gerar o QR Code do PIX usando a chave PIX da loja
        // Por enquanto, vamos apenas mostrar os dados do PIX
        $pixData = [
            'key' => 'chave_pix_da_loja@email.com', // Substituir pela chave PIX real
            'amount' => $order->total_amount,
            'description' => "Pedido #{$order->id}"
        ];

        session()->put('pix_data', $pixData);
        
        // Enviar e-mail para a loja com os dados do pedido
        $this->sendNewOrderEmail($order);
    }

    private function sendNewOrderEmail($order)
    {
        // Aqui você implementaria o envio do e-mail
        // Por enquanto vamos apenas registrar no log
        \Log::info('Novo pedido recebido', [
            'order_id' => $order->id,
            'customer' => $order->customer_name,
            'amount' => $order->total_amount,
            'payment_method' => $order->payment_method
        ]);
    }

    public function success(Order $order)
    {
        return view('orders.success', compact('order'));
    }
}
