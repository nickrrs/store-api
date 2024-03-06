<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $products = Product::factory(5)->create();
        $sales = Sale::factory(2)->create();

        foreach ($sales as $sale) {
            $productsToAssociateCount = rand(1, min(3, $products->count()));
            
            $productsToAssociate = $products->random($productsToAssociateCount);
            
            foreach ($productsToAssociate as $product) {
                SaleProduct::factory()->create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'amount' => rand(1, 5)
                ]);
            }
        }
    }
}
