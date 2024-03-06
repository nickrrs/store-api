<?php

namespace Tests\Unit;

use App\Domain\Products\Application\Services\ProductService;
use App\Domain\Sales\Application\Services\SaleService;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Observers\SaleProductObserver;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class SaleProductObserverTest extends TestCase
{
    use RefreshDatabase;

    private $loggerMock;

    public function test_sale_amount_shoul_reflect_products_price_versus_the_amount_added_to_that_sale(): void
    {
        $product = Product::factory()->create(['price' => 100]);
        $sale = Sale::factory()->create();
        SaleProduct::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'amount' => 3
        ]);

        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'amount' => 300
        ]);
    }
}
