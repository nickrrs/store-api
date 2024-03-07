<?php

namespace App\Domain\Sales\Application\Actions;

use App\Domain\Sales\Application\Repositories\SaleRepository;
use App\Models\Sale;

class SalesCommandActions
{
    public function __construct(private SaleRepository $saleRepository)
    {
    }

    public function newSale(array $payload): Sale
    {
        return $this->saleRepository->store($payload);
    }

    public function cancel(Sale $sale): Sale
    {
        return $this->saleRepository->cancel($sale);
    }
}
