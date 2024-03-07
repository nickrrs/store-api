<?php

namespace App\Domain\Sales\Infrastructure\Contracts;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

interface SaleRepositoryContract
{
    public function all(): Collection;
    public function getSale(string $id): Sale;   
    public function store(array $paylaod): Sale;
    public function cancel(Sale $sale): Sale;
}
