<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Product::whereRaw('LOWER(name) = ?', ['logo'])->delete();
        
        $productImages = \Storage::disk('public')->files('products');

        foreach ($productImages as $image) {
            // Pula arquivos que não são imagens (opcional)
            if (!preg_match('/\\.(jpg|jpeg|png|gif)$/i', $image)) {
                continue;
            }

            // Usa o nome do arquivo (sem extensão) como nome do produto
            $name = ucfirst(pathinfo($image, PATHINFO_FILENAME));

            // Não cadastra o produto chamado 'Logo'
            if (strtolower($name) === 'logo') {
                continue;
            }

            \App\Models\Product::create([
                'name' => $name,
                'description' => 'Produto artesanal exclusivo.',
                'price' => 100.00,
                'stock' => 10,
                'image' => $image,
                'active' => true,
            ]);
        }
    }    
}
