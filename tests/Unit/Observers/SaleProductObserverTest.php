<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
