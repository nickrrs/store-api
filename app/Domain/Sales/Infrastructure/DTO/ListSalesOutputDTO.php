<?php

namespace App\Domain\Sales\Infrastructure\DTO;

class ListSalesOutputDTO
{
    private $sales;

    public function __construct(array $salesData)
    {
        $this->sales = array_map(function ($saleData) {
            return [
                'id' => $saleData['id'],
                'status' => $saleData['status'],
                'updated_at' => $saleData['updated_at'],
                'created_at' => $saleData['created_at'],
                'products' => array_map(function ($product) {
                    return [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'amount' => $product['pivot']['amount'] ?? null,
                    ];
                }, $saleData['products'] ?? [])
            ];
        }, $salesData);
    }

    public static function fromArray(array $salesData): self
    {
        return new self($salesData);
    }

    public function response(): array
    {
        return [
            'message' => 'Sales retrieved successfully.',
            'data' => $this->sales,
        ];
    }
}
