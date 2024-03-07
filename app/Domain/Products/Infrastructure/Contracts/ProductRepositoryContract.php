<?php

namespace App\Domain\Products\Infrastructure\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryContract
{
    public function all(): Collection;
    public function show(string $id): Product;
}
