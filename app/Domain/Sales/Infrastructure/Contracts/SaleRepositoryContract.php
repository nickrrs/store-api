<?php

namespace App\Domain\Sales\Infrastructure\Contracts;

use App\Models\Sale;

interface SaleRepositoryContract
{
    public function getSale(string $id): Sale;   
}
