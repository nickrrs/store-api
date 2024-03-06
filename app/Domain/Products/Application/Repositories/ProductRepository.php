<?php

namespace App\Domain\Products\Application\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function show(string $id): Product
    {
        return Product::findOrFail($id);
    }
}
