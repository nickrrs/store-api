<?php

namespace App\Domain\Products\Infrastructure\Contracts;

use App\Models\Product;

interface ProductRepositoryContract
{
    public function show(string $id): Product;
}
