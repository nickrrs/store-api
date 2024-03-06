<?php

namespace App\Domain\Sales\Application\Actions;

use App\Domain\Sales\Application\Repositories\SaleRepository;
use App\Models\Sale;

class SalesQueryActions
{
    public function __construct(private SaleRepository $saleRepository)
    {
    }

    public function getSale(string $id): Sale
    {
        return $this->saleRepository->getSale($id);
    }
}
