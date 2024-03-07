<?php

namespace App\Domain\Products\Application\Services;

use App\Domain\Products\Application\Actions\ProductsQueryActions;
use App\Domain\Products\Infrastructure\Contracts\ProductServiceContract;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService implements ProductServiceContract
{
    public function __construct(private ProductsQueryActions $productQueryAction)
    {
    }

    public function getProducts(): Collection
    {
        return $this->productQueryAction->getProducts();
    }
    
    public function getProduct(string $id): Product
    {
        return $this->productQueryAction->getProduct($id);
    }
}
