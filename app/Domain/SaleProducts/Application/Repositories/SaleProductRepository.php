<?php

namespace App\Domain\SaleProducts\Application\Repositories;

use App\Models\SaleProduct;

class SaleProductRepository
{
    public function store(array $payload): SaleProduct
    {
        return SaleProduct::create($payload);
    }
}
