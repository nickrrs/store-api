<?php

namespace App\Domain\Sales\Application\Repositories;

use App\Models\Sale;

class SaleRepository
{

    public function getSale(string $id): Sale
    {
        return Sale::findOrFail($id);
    }
}
