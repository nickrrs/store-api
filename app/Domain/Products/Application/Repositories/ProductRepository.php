<?php

namespace App\Domain\Products\Application\Repositories;

use App\Domain\Products\Infrastructure\Contracts\ProductRepositoryContract;
use App\Models\Product;

class ProductRepository implements ProductRepositoryContract
{
    public function show(string $id): Product
    {
        return Product::findOrFail($id);
    }
}
