<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function login()
    {
        if (Session::has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Senha definida diretamente para simplificar
        // Em produção, isso deve vir do banco de dados
        $adminUsername = 'admin';
        $adminPassword = 'admin123';

        if ($request->username === $adminUsername && $request->password === $adminPassword) {
            Session::put('admin_logged_in', true);
            return redirect()->route('admin.dashboard')->with('success', 'Bem-vindo ao painel administrativo!');
        }

        return back()->with('error', 'Credenciais inválidas!');
    }

    public function dashboard()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $totalProducts = Product::count();
        $recentOrders = Order::with('items')->latest()->take(5)->get();

        // Dados para o gráfico de vendas do mês atual
        $salesData = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesLabels = $salesData->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d/m');
        });
        $salesTotals = $salesData->pluck('total');

        return view('admin.dashboard', compact('totalOrders', 'pendingOrders', 'totalProducts', 'recentOrders', 'salesLabels', 'salesTotals'));
    }

    public function orders()
    {
        $orders = Order::with('items')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function orderUpdate(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update([
            'order_status' => $request->order_status
        ]);

        return back()->with('success', 'Status do pedido atualizado com sucesso!');
    }

    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login')->with('success', 'Logout realizado com sucesso!');
    }

    // Gerenciamento de Produtos
    public function products()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function productCreate()
    {
        return view('admin.products.create');
    }

    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produto criado com sucesso!');
    }

    public function productEdit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function productUpdate(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Remove imagem antiga se existir
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function productDestroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produto excluído com sucesso!');
    }
}
