<?php

namespace App\Domain\SaleProducts\Application\Services;

use App\Domain\SaleProducts\Application\Actions\SaleProductCommands;
use App\Models\SaleProduct;

class SaleProductService
{
    public function __construct(private SaleProductCommands $saleProductCommands)
    {
    }

    public function newSaleProduct(array $payload): SaleProduct
    {
        return $this->saleProductCommands->newSaleProduct($payload);
    }
}
