<?php

namespace App\Domain\Sales\Application\Repositories;

use App\Domain\Sales\Infrastructure\Contracts\SaleRepositoryContract;
use App\Models\Sale;

class SaleRepository implements SaleRepositoryContract
{

    public function getSale(string $id): Sale
    {
        return Sale::findOrFail($id);
    }

    public function store(array $paylaod): Sale
    {
        return Sale::create($paylaod);
    }
}
