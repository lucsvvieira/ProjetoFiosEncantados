<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Se for admin, permite busca e filtros
        if (request()->route()->getName() === 'admin.products.index') {
            $query = Product::query();
            
            // Busca por nome
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            // Filtro por status
            if ($request->filled('status')) {
                $query->where('active', $request->status === 'ativo');
            }
            // Filtro por estoque
            if ($request->filled('stock')) {
                if ($request->stock === 'sem') {
                    $query->where('stock', 0);
                } elseif ($request->stock === 'baixo') {
                    $query->where('stock', '>', 0)->where('stock', '<=', 5);
                } elseif ($request->stock === 'ok') {
                    $query->where('stock', '>', 5);
                }
            }
            $products = $query->latest()->paginate(10)->appends($request->query());
            return view('admin.products.index', compact('products'));
        }
        // Público
        $products = Product::where('active', true)->paginate(8);
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $validated['active'] = true;

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto excluído com sucesso!');
    }
}
