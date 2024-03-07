<?php

namespace App\Domain\SaleProducts\Application\Repositories;

use App\Domain\SaleProducts\Infrastructure\Contracts\SaleProductRepositoryContract;
use App\Models\SaleProduct;

class SaleProductRepository implements SaleProductRepositoryContract
{
    public function getSaleProduct(string $productId): SaleProduct
    {
        return SaleProduct::where('producT_id', $productId)->firstOrFail();
    }

    public function store(array $payload): SaleProduct
    {
        return SaleProduct::create($payload);
    }

    public function update(SaleProduct $saleProduct, array $payload): SaleProduct
    {
        $saleProduct->update($payload);

        return $saleProduct;
    }
}
