<?php

namespace App\Domain\Sales\Application\Actions;

use App\Domain\Sales\Application\Repositories\SaleRepository;

class SalesCommandActions
{
    public function __construct(private SaleRepository $saleRepository)
    {
    }

    public function newSale(array $payload)
    {
        return $this->saleRepository->store($payload);
    }
}
