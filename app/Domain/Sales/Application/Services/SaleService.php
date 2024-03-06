<?php

namespace App\Domain\Sales\Application\Services;

use App\Domain\Sales\Application\Actions\SalesQueryActions;
use App\Models\Sale;

class SaleService
{
    public function __construct(private SalesQueryActions $salesQueryAction)
    {
    }

    public function getSale(string $id): Sale
    {
        return $this->salesQueryAction->getSale($id);
    }
}
    
