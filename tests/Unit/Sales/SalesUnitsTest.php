<?php

namespace Tests\Unit;

use App\Domain\Products\Application\Actions\ProductsQueryActions;
use App\Domain\Products\Application\Repositories\ProductRepository;
use App\Domain\Products\Application\Services\ProductService;
use App\Domain\SaleProducts\Application\Actions\SaleProductCommands;
use App\Domain\SaleProducts\Application\Repositories\SaleProductRepository;
use App\Domain\SaleProducts\Application\Services\SaleProductService;
use App\Domain\Sales\Application\Actions\SalesCommandActions;
use App\Domain\Sales\Application\Actions\SalesQueryActions;
use App\Domain\Sales\Application\Repositories\SaleRepository;
use App\Domain\Sales\Application\Services\SaleService;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesUnitsTest extends TestCase
{
    use RefreshDatabase;

    private $salesQueryAction;
    private $salesCommandActions;
    private $productQueryAction;
    private $saleProductCommands;

    private $saleRepository;
    private $productRepository;
    private $saleProductRepository;

    private $saleService;
    private $productService;
    private $saleProductService;

    public function setUp(): void
    {
        parent::setUp();
        $this->initializeRepositories();
        $this->initializeQueryActions();
        $this->initializeCommandActions();
        $this->initializeServices();
    }

    public function test_new_sale(): void
    {
        $product = Product::factory()->create();
        
        $newSalePayload = [
            'products' => [[
                'product_id' => $product->id,
                'amount' => 2
            ]],
        ];

        $sale = $this->saleService->newSale($newSalePayload);
        $this->assertDatabaseHas('sales', [
            'id' => $sale->id
        ]);

        $this->assertDatabaseHas('sale_products', [
            'sale_id' => $sale->id,
            'product_id' => $product->id
        ]);
    }

    private function initializeRepositories(): void
    {
        $this->saleRepository = new SaleRepository();
        $this->productRepository = new ProductRepository();
        $this->saleProductRepository = new SaleProductRepository();
    }

    private function initializeQueryActions(): void
    {
        $this->salesQueryAction = new SalesQueryActions($this->saleRepository);
        $this->productQueryAction = new ProductsQueryActions($this->productRepository);
    }

    private function initializeCommandActions(): void
    {
        $this->salesCommandActions = new SalesCommandActions($this->saleRepository);
        $this->saleProductCommands = new SaleProductCommands($this->saleProductRepository);
    }

    private function initializeServices(): void
    {
        $this->productService = new ProductService($this->productQueryAction);
        $this->saleProductService = new SaleProductService($this->saleProductCommands);
        $this->saleService = new SaleService(
            $this->salesQueryAction,
            $this->salesCommandActions,
            $this->saleProductService,
            $this->productService
        );
    }
}
