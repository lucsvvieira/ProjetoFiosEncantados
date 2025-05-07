<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'cep' => 'required|string|size:8',
            'total' => 'required|numeric'
        ]);

        try {
            // Consulta o CEP na API ViaCEP
            $response = Http::get("https://viacep.com.br/ws/{$request->cep}/json/");
            
            if ($response->failed() || isset($response['erro'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'CEP não encontrado'
                ], 400);
            }

            $address = $response->json();
            
            // Simulação de cálculo de frete
            $shippingCost = $this->calculateShippingCost($request->total, $address['uf']);

            return response()->json([
                'success' => true,
                'address' => $address,
                'shipping_cost' => $shippingCost
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao calcular o frete'
            ], 500);
        }
    }

    private function calculateShippingCost($total, $uf)
    {
        // Exemplo de cálculo de frete baseado no estado
        $baseCost = 15.00;
        
        // Estados mais distantes têm frete mais caro
        $northStates = ['AC', 'AP', 'AM', 'PA', 'RO', 'RR', 'TO'];
        $northeastStates = ['AL', 'BA', 'CE', 'MA', 'PB', 'PE', 'PI', 'RN', 'SE'];
        
        if (in_array($uf, $northStates)) {
            return $baseCost * 2;
        } elseif (in_array($uf, $northeastStates)) {
            return $baseCost * 1.5;
        }
        
        // Frete grátis para compras acima de R$ 200
        if ($total >= 200) {
            return 0;
        }
        
        return $baseCost;
    }
} 