<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ShippingService
{
    protected $baseUrl = 'https://viacep.com.br/ws/';
    protected $cacheTime = 60 * 24; // 24 horas

    public function calculateShipping($cep, $total)
    {
        // Validação básica do CEP
        $cep = preg_replace('/[^0-9]/', '', $cep);
        if (strlen($cep) !== 8) {
            return [
                'success' => false,
                'message' => 'CEP inválido'
            ];
        }

        // Busca informações do CEP
        $address = $this->getAddressByCep($cep);
        if (!$address) {
            return [
                'success' => false,
                'message' => 'CEP não encontrado'
            ];
        }

        // Regras de frete (você pode ajustar conforme sua necessidade)
        $shippingCost = $this->calculateShippingCost($address['uf'], $total);

        return [
            'success' => true,
            'address' => $address,
            'shipping_cost' => $shippingCost,
            'estimated_days' => $this->getEstimatedDeliveryDays($address['uf'])
        ];
    }

    protected function getAddressByCep($cep)
    {
        return Cache::remember("cep_{$cep}", $this->cacheTime, function () use ($cep) {
            $response = Http::get("{$this->baseUrl}{$cep}/json");
            
            if ($response->successful() && !isset($response['erro'])) {
                return [
                    'cep' => $response['cep'],
                    'logradouro' => $response['logradouro'],
                    'bairro' => $response['bairro'],
                    'localidade' => $response['localidade'],
                    'uf' => $response['uf']
                ];
            }
            
            return null;
        });
    }

    protected function calculateShippingCost($uf, $total)
    {
        // Regras de frete por região
        $regions = [
            'NORTE' => ['AC', 'AP', 'AM', 'PA', 'RO', 'RR', 'TO'],
            'NORDESTE' => ['AL', 'BA', 'CE', 'MA', 'PB', 'PE', 'PI', 'RN', 'SE'],
            'CENTRO-OESTE' => ['DF', 'GO', 'MT', 'MS'],
            'SUDESTE' => ['ES', 'MG', 'RJ', 'SP'],
            'SUL' => ['PR', 'RS', 'SC']
        ];

        // Identifica a região
        $region = '';
        foreach ($regions as $reg => $states) {
            if (in_array($uf, $states)) {
                $region = $reg;
                break;
            }
        }

        // Valores base do frete por região
        $baseValues = [
            'NORTE' => 25.00,
            'NORDESTE' => 20.00,
            'CENTRO-OESTE' => 15.00,
            'SUDESTE' => 10.00,
            'SUL' => 12.00
        ];

        // Frete grátis para compras acima de R$ 200,00
        if ($total >= 200) {
            return 0;
        }

        return $baseValues[$region] ?? 15.00;
    }

    protected function getEstimatedDeliveryDays($uf)
    {
        // Prazo estimado de entrega por região
        $deliveryDays = [
            'NORTE' => 10,
            'NORDESTE' => 8,
            'CENTRO-OESTE' => 5,
            'SUDESTE' => 3,
            'SUL' => 4
        ];

        // Identifica a região
        $regions = [
            'NORTE' => ['AC', 'AP', 'AM', 'PA', 'RO', 'RR', 'TO'],
            'NORDESTE' => ['AL', 'BA', 'CE', 'MA', 'PB', 'PE', 'PI', 'RN', 'SE'],
            'CENTRO-OESTE' => ['DF', 'GO', 'MT', 'MS'],
            'SUDESTE' => ['ES', 'MG', 'RJ', 'SP'],
            'SUL' => ['PR', 'RS', 'SC']
        ];

        foreach ($regions as $region => $states) {
            if (in_array($uf, $states)) {
                return $deliveryDays[$region];
            }
        }

        return 5; // Prazo padrão
    }
} 