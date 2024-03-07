<?php

namespace App\Domain\SaleProducts\Application\Services;

use App\Domain\SaleProducts\Application\Actions\SaleProductCommands;
use App\Domain\SaleProducts\Infrastructure\Contracts\SaleProductServiceContract;
use App\Models\SaleProduct;

class SaleProductService implements SaleProductServiceContract
{
    public function __construct(private SaleProductCommands $saleProductCommands)
    {
    }

    public function getSaleProductByProduct(string $productId): SaleProduct
    {
        return $this->saleProductCommands->getSaleProductByProduct($productId);
    }

    public function newSaleProduct(array $payload): SaleProduct
    {
        return $this->saleProductCommands->newSaleProduct($payload);
    }

    public function updateSaleProduct(SaleProduct $saleProduct, array $payload): SaleProduct
    {
        return $this->saleProductCommands->updateSaleProduct($saleProduct, $payload);
    }
}
