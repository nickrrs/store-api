<?php

namespace App\Domain\SaleProducts\Infrastructure\Contracts;

use App\Models\SaleProduct;

interface SaleProductServiceContract
{
    public function newSaleProduct(array $payload): SaleProduct;
    public function updateSaleProduct(SaleProduct $saleProduct, array $payload): SaleProduct;
}
