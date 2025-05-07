<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!session()->has('cart') || empty(session('cart'))) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        return view('checkout');
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cep' => 'required|string|max:9',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'payment_method' => 'required|in:pix,credit_card',
        ]);

        $cart = session('cart');
        $total = array_sum(array_map(function($item) { 
            return $item['price'] * $item['quantity']; 
        }, $cart)) + 10; // Adiciona o frete

        // Configura o Mercado Pago
        SDK::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN'));

        // Cria uma preferência de pagamento
        $preference = new Preference();

        // Cria os itens do pedido
        $items = [];
        foreach ($cart as $id => $item) {
            $items[] = [
                'title' => $item['name'],
                'quantity' => $item['quantity'],
                'currency_id' => 'BRL',
                'unit_price' => $item['price']
            ];
        }

        // Adiciona o frete como item
        $items[] = [
            'title' => 'Frete',
            'quantity' => 1,
            'currency_id' => 'BRL',
            'unit_price' => 10
        ];

        $preference->items = $items;

        // Configura as URLs de retorno
        $preference->back_urls = [
            'success' => route('checkout.success'),
            'failure' => route('checkout.cancel'),
            'pending' => route('checkout.pending')
        ];

        // Configura o pagador
        $preference->payer = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => [
                'number' => $request->phone
            ],
            'address' => [
                'street_name' => $request->address,
                'street_number' => $request->number,
                'neighborhood' => $request->neighborhood,
                'city' => $request->city,
                'federal_unit' => $request->state,
                'zip_code' => $request->cep
            ]
        ];

        // Configura o método de pagamento
        if ($request->payment_method === 'pix') {
            $preference->payment_methods = [
                'excluded_payment_types' => [
                    ['id' => 'credit_card'],
                    ['id' => 'debit_card'],
                    ['id' => 'bank_transfer']
                ]
            ];
        } else {
            $preference->payment_methods = [
                'excluded_payment_types' => [
                    ['id' => 'pix']
                ]
            ];
        }

        // Salva a preferência
        $preference->save();

        // Armazena os dados do pedido na sessão
        session(['pending_order' => [
            'preference_id' => $preference->id,
            'customer' => [
                'name' => $request->name,
                'email' => $request->email,
            ],
            'address' => [
                'street' => $request->address,
                'number' => $request->number,
                'complement' => $request->complement,
                'neighborhood' => $request->neighborhood,
                'city' => $request->city,
                'state' => $request->state,
                'cep' => $request->cep,
            ],
        ]]);

        // Redireciona para a página de pagamento do Mercado Pago
        return redirect($preference->init_point);
    }

    public function success()
    {
        // Limpa o carrinho
        session()->forget('cart');
        
        return view('checkout.success');
    }

    public function cancel()
    {
        return redirect()->route('cart.index')
            ->with('error', 'Pagamento cancelado. Por favor, tente novamente.');
    }

    public function pending()
    {
        return view('checkout.pending');
    }
} 