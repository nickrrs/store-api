<?php

namespace App\Domain\Products\Infrastructure\Contracts;

use App\Models\Product;

interface ProductServiceContract
{
    public function getProduct(string $id): Product;
}
