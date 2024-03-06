<?php

namespace App\Domain\Products\Application\Services;

use App\Domain\Products\Application\Actions\ProductsQueryActions;
use App\Models\Product;

class ProductService
{
    public function __construct(private ProductsQueryActions $productQueryAction)
    {
    }

    public function getProduct(string $id): Product
    {
        return $this->productQueryAction->getProduct($id);
    }
}
