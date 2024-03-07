<?php

namespace App\Domain\SaleProducts\Infrastructure\Contracts;

use App\Models\SaleProduct;

interface SaleProductRepositoryContract
{
    public function store(array $payload): SaleProduct;
    public function update(SaleProduct $saleProduct, array $payload): SaleProduct;
}
