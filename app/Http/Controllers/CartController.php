<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $items = [];

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            
            if ($product && $product->active) {
                // Atualiza o preço e verifica o estoque
                $item['price'] = $product->price;
                $item['max_quantity'] = $product->stock;
                
                // Ajusta a quantidade se exceder o estoque
                if ($item['quantity'] > $product->stock) {
                    $item['quantity'] = $product->stock;
                    $cart[$id]['quantity'] = $product->stock;
                }
                
                $items[$id] = $item;
                $total += $item['price'] * $item['quantity'];
            } else {
                // Remove produtos inativos ou inexistentes
                unset($cart[$id]);
            }
        }

        // Atualiza o carrinho na sessão
        session()->put('cart', $cart);

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        if (!$product->active) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Este produto não está disponível para compra.']);
            }
            return redirect()->back()->with('error', 'Este produto não está disponível para compra.');
        }

        if ($product->stock < $request->quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Quantidade solicitada indisponível em estoque.']);
            }
            return redirect()->back()->with('error', 'Quantidade solicitada indisponível em estoque.');
        }

        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity');

        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
            
            if ($newQuantity > $product->stock) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Quantidade total excede o estoque disponível.']);
                }
                return redirect()->back()->with('error', 'Quantidade total excede o estoque disponível.');
            }
            
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image
            ];
        }

        // Garante que a sessão seja persistida
        session()->put('cart', $cart);
        session()->save();

        \Log::info('Carrinho atualizado:', ['cart' => $cart]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produto adicionado ao carrinho!',
                'cartCount' => array_sum(array_column($cart, 'quantity'))
            ]);
        }

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Produto removido do carrinho!');
        }

        return redirect()->back()->with('error', 'Produto não encontrado no carrinho.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $quantity = $request->input('quantity');
            
            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', 'Quantidade solicitada indisponível em estoque.');
            }

            $cart[$product->id]['quantity'] = $quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Carrinho atualizado!');
        }

        return redirect()->back()->with('error', 'Produto não encontrado no carrinho.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Carrinho esvaziado!');
    }
}
