<?php

namespace App\Domain\Sales\Application\Repositories;

use App\Domain\Sales\Infrastructure\Contracts\SaleRepositoryContract;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

class SaleRepository implements SaleRepositoryContract
{

    public function all(): Collection
    {
        return Sale::with('products')->get();
    }

    public function getSale(string $id): Sale
    {
        return Sale::with('products')->findOrFail($id);
    }

    public function store(array $paylaod): Sale
    {
        return Sale::create($paylaod);
    }
}
