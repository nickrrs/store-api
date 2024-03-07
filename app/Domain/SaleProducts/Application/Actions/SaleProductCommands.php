<?php

namespace App\Domain\SaleProducts\Application\Actions;

use App\Domain\SaleProducts\Application\Repositories\SaleProductRepository;
use App\Models\SaleProduct;

class SaleProductCommands
{
    public function __construct(private SaleProductRepository $saleProductRepository)
    {
    }

    public function newSaleProduct(array $payload): SaleProduct
    {
        return $this->saleProductRepository->store($payload);
    }
}
