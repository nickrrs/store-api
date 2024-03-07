<?php

namespace App\Domain\Products\Infrastructure\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductServiceContract
{
    public function getProducts(): Collection;
    public function getProduct(string $id): Product;
}
