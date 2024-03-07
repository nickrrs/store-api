<?php

namespace App\Domain\Sales\Infrastructure\Contracts;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

interface SaleServiceContract
{
    public function getSales(): Collection;
    public function getSale(string $id): Sale;
    public function newSale(array $payload): Sale;
    public function addProduct(array $payload, string $id): Sale;
    public function cancel(string $id): Sale;
}
