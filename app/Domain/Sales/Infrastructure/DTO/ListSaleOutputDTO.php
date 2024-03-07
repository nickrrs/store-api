<?php

namespace App\Domain\Sales\Infrastructure\DTO;

class ListSaleOutputDTO
{
    private $saleData;

    public function __construct(array $saleData)
    {
        $this->saleData = $this->formatSaleData($saleData);
    }

    private function formatSaleData(array $saleData): array
    {
        return [
            'id' => $saleData['id'],
            'status' => $saleData['status'],
            'amount' => $saleData['amount'],
            'updated_at' => $saleData['updated_at'],
            'created_at' => $saleData['created_at'],
            'products' => array_map(function ($product) {
                return [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'amount' => $product['pivot']['amount'] ?? $product['amount'],
                ];
            }, $saleData['products'] ?? [])
        ];
    }

    public static function fromArray(array $saleData): self
    {
        return new self($saleData);
    }

    public function response(): array
    {
        return [
            'message' => 'Sale retrieved successfully.',
            'data' => $this->saleData,
        ];
    }
}
