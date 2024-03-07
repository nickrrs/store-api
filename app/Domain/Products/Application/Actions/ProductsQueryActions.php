<?php

namespace App\Domain\Products\Application\Actions;

use App\Domain\Products\Application\Repositories\ProductRepository;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductsQueryActions
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getProducts(): Collection
    {
        return $this->productRepository->all();
    }

    public function getProduct(string $id): Product
    {
        return $this->productRepository->show($id);
    }
}
