<?php

namespace Tests\Unit;

use App\Domain\Products\Application\Actions\ProductsQueryActions;
use App\Domain\Products\Application\Repositories\ProductRepository;
use App\Domain\Products\Application\Services\ProductService;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsUnitsTest extends TestCase
{
    use RefreshDatabase;

    private $productService;

    public function setUp(): void
    {
        parent::setUp();
        $this->productService = new ProductService(new ProductsQueryActions(new ProductRepository()));
    }

    public function test_list_registered_products(): void
    {
        Product::factory(10)->create();
        $products = $this->productService->getProducts();

        $this->assertGreaterThan(0, $products->count());
    }
}
