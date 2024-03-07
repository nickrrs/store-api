<?php

namespace Tests\Feature;

use App\Domain\Products\Application\Services\ProductService;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_all_products(): void
    {
        Product::factory(5)->create();
        $request = $this->get(route('product.all'));

        $request->assertStatus(200);
        $request->assertJsonIsArray();
    }

    public function test_no_product_was_found_when_listing(): void
    {
        $request = $this->get(route('product.all'));

        $request->assertStatus(422);
        $request->assertJson([
            'No product was found in the database, please try again later.'
        ]);
    }

    public function test_exception_throw_on_list_products_route(): void
    {
        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('getProducts')->once()->andThrow(new \Exception('Erro interno', 500));
        });

        $request = $this->get(route('product.all'));

        $request->assertStatus(500);
        $request->assertJson([
            'errors' => [
                'message' => 'Erro interno',
            ],
        ]);
    }
}
