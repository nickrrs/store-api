<?php

namespace Tests\Feature;

use App\Domain\Products\Infrastructure\Exceptions\ProductNotFoundException;
use App\Domain\Sales\Application\Services\SaleService;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
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
}
