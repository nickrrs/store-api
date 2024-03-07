<?php

namespace Tests\Feature;

use App\Domain\Products\Infrastructure\Exceptions\ProductNotFoundException;
use App\Domain\Sales\Application\Services\SaleService;
use App\Domain\Sales\Infrastructure\Enums\SaleStatusesEnum;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_create_a_new_store_you_should_send_the_products(): void
    {
        $payload = [
            'products' => []
        ];

        $request = $this->post(route('sale.store'), $payload);
        $request->assertStatus(422);
    }

    public function test_exception_throw_on_new_sale_route(): void
    {
        $this->mock(SaleService::class, function ($mock) {
            $mock->shouldReceive('newSale')->once()->andThrow(new ProductNotFoundException('A choosen product was not found in the database'));
        });

        $payload = [
            'products' => [[
                'product_id' => 'test',
                'amount' => 1
            ]]
        ];

        $request = $this->post(route('sale.store'), $payload);

        $request->assertStatus(422);
        $request->assertJson([
            'errors' => [
                'message' => 'A choosen product was not found in the database',
            ],
        ]);
    }

    public function test_new_sale_route(): void
    {
        $product = Product::factory()->create();

        $payload = [
            'products' => [[
                'product_id' => $product->id,
                'amount' => 2
            ]]
        ];

        $request = $this->post(route('sale.store'), $payload)->assertStatus(200);
        $responseData = $request->json()['data'];

        $this->assertDatabaseHas('sales', [
            'id' => $responseData['id'],
            'amount' => $product->price * $payload['products'][0]['amount']
        ]);
    }

    public function test_list_all_sales(): void
    {
        $product = Product::factory()->create();

        $payload = [
            'products' => [[
                'product_id' => $product->id,
                'amount' => 2
            ]]
        ];

        $this->post(route('sale.store'), $payload)->assertStatus(200);

        $request = $this->get(route('sale.all'))->assertStatus(200);
        $data = $request->json();

        $this->assertIsArray($data['data']);

        $foundProducts = false;
        foreach ($data['data'] as $item) {
            if (array_key_exists('products', $item)) {
                $foundProducts = true;
                break;
            }
        }

        $this->assertTrue($foundProducts);
    }

    public function test_no_sale_was_found_when_listing(): void
    {
        $request = $this->get(route('sale.all'));

        $request->assertStatus(422);
        $request->assertJson([
            'No sale was found in the database, please try again later.'
        ]);
    }

    public function test_retrieve_a_sale(): void
    {
        $product = Product::factory()->create();

        $payload = [
            'products' => [[
                'product_id' => $product->id,
                'amount' => 2
            ]]
        ];

        $newSaleRequest = $this->post(route('sale.store'), $payload)->assertStatus(200);
        $saleData = $newSaleRequest->json();

        $getRequest = $this->get(route('sale.get', ['id' => $saleData['data']['id']]))->assertStatus(200);
        $data = $getRequest->json();

        $this->assertIsArray($data['data']);

        $foundProducts = false;
        if (array_key_exists('products', $data['data'])) {
            $foundProducts = true;
        }

        $this->assertTrue($foundProducts);
    }

    public function test_cancel_a_sale_route(): void
    {
        $product = Product::factory()->create();

        $payload = [
            'products' => [[
                'product_id' => $product->id,
                'amount' => 2
            ]]
        ];

        $newSaleRequest = $this->post(route('sale.store'), $payload)->assertStatus(200);
        $saleData = $newSaleRequest->json();

        $this->patch(route('sale.cancel', ['id' => $saleData['data']['id']]))->assertStatus(200);
        $this->assertDatabaseHas('sales', [
            'id' => $saleData['data']['id'],
            'status' => SaleStatusesEnum::Cancelled->value
        ]);
    }

    public function test_exception_when_trying_to_cancel_a_already_cancelled_sale(): void
    {
        $product = Product::factory()->create();

        $payload = [
            'products' => [[
                'product_id' => $product->id,
                'amount' => 2
            ]]
        ];

        $newSaleRequest = $this->post(route('sale.store'), $payload)->assertStatus(200);
        $saleData = $newSaleRequest->json();

        $this->patch(route('sale.cancel', ['id' => $saleData['data']['id']]))->assertStatus(200);
        $this->patch(route('sale.cancel', ['id' => $saleData['data']['id']]))->assertStatus(422);
    }

    public function test_add_a_new_product_to_an_existing_sale(): void
    {
        $productOne = Product::factory()->create();
        $productTwo = Product::factory()->create();

        $payload = [
            'products' => [[
                'product_id' => $productOne->id,
                'amount' => 2
            ]]
        ];

        $saleRequest = $this->post(route('sale.store'), $payload)->assertStatus(200);
        $saleData = $saleRequest->json();

        $addProductPayload = [
            'products' => [[
                'product_id' => $productTwo->id,
                'amount' => 1
            ]]
        ];

        $this->post(route('sale.add', ['id' => $saleData['data']['id']]), $addProductPayload)->assertStatus(200);

        $this->assertDatabaseHas('sale_products', [
            'sale_id' => $saleData['data']['id'],
            'product_id' => $productTwo->id,
            'amount' => 1
        ]);
    }

    public function test_add_the_same_product_to_an_existing_sale(): void
    {
        $productOne = Product::factory()->create();

        $payload = [
            'products' => [[
                'product_id' => $productOne->id,
                'amount' => 2
            ]]
        ];

        $saleRequest = $this->post(route('sale.store'), $payload)->assertStatus(200);
        $saleData = $saleRequest->json();

        $addProductPayload = [
            'products' => [[
                'product_id' => $productOne->id,
                'amount' => 1
            ]]
        ];

        $this->post(route('sale.add', ['id' => $saleData['data']['id']]), $addProductPayload)->assertStatus(200);

        $this->assertDatabaseHas('sale_products', [
            'sale_id' => $saleData['data']['id'],
            'product_id' => $productOne->id,
            'amount' => 3
        ]);

        $this->assertDatabaseHas('sales', [
            'id' => $saleData['data']['id'],
            'amount' => $productOne->price * 3
        ]);
    }

    public function test_add_a_product_to_an_cancelled_sale(): void
    {
        $productOne = Product::factory()->create();
        $productTwo = Product::factory()->create();

        $sale = Sale::factory()->create([
            'status' => 'cancelled'
        ]);

        SaleProduct::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $productOne->id,
            'amount' => 1
        ]); 

        $addProductPayload = [
            'products' => [[
                'product_id' => $productTwo->id,
                'amount' => 1
            ]]
        ];

        $this->post(route('sale.add', ['id' => $sale->id]), $addProductPayload)->assertStatus(422);
    }
}
