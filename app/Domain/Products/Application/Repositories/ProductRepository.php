<?php

namespace App\Domain\Products\Application\Repositories;

use App\Domain\Products\Infrastructure\Contracts\ProductRepositoryContract;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryContract
{
    public function all(): Collection
    {
        return Product::all();
    }

    public function show(string $id): Product
    {
        return Product::findOrFail($id);
    }
}
