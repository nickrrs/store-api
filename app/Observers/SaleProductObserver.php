<?php

namespace App\Observers;

use App\Domain\Products\Application\Services\ProductService;
use App\Domain\Sales\Application\Services\SaleService;
use App\Models\SaleProduct;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SaleProductObserver
{

    public function __construct(private ProductService $productService, private SaleService $saleService)
    {
    }

    public function created(SaleProduct $saleProduct): void
    {
        try {
            $product = $this->productService->getProduct($saleProduct->product_id);
            $sale = $this->saleService->getSale($saleProduct->sale_id);
    
            $sale->update([
                'amount' => $sale->amount + ($saleProduct->amount * $product->price)
            ]);
        } catch (QueryException $e) {
            Log::error('Error on SaleProduct Observer while trying to associate the amount to the sale [ID: ' . $sale->id . ']', ['error' => $e->getMessage(), 'status' => $e->getCode()]);
        }        
    }

    public function updated(SaleProduct $saleProduct): void
    {
        //
    }
}
