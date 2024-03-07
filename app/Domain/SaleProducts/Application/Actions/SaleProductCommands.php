<?php

namespace App\Domain\SaleProducts\Application\Actions;

use App\Domain\SaleProducts\Application\Repositories\SaleProductRepository;
use App\Models\SaleProduct;

class SaleProductCommands
{
    public function __construct(private SaleProductRepository $saleProductRepository)
    {
    }

    public function getSaleProductByProduct(string $productId): SaleProduct
    {
        return $this->saleProductRepository->getSaleProduct($productId);   
    }

    public function newSaleProduct(array $payload): SaleProduct
    {
        return $this->saleProductRepository->store($payload);
    }

    public function updateSaleProduct(SaleProduct $saleProduct, array $payload): SaleProduct
    {
        return $this->saleProductRepository->update($saleProduct, $payload);
    }
}
