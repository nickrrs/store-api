<?php

namespace App\Domain\Sales\Infrastructure\Contracts;

use App\Models\Sale;

interface SaleServiceContract
{
    public function getSale(string $id): Sale;
}
