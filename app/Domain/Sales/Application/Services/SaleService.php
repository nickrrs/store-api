<?php

namespace App\Domain\Sales\Application\Services;

use App\Domain\Products\Application\Services\ProductService;
use App\Domain\Products\Infrastructure\Exceptions\ProductNotFoundException;
use App\Domain\SaleProducts\Application\Services\SaleProductService;
use App\Domain\Sales\Application\Actions\SalesCommandActions;
use App\Domain\Sales\Application\Actions\SalesQueryActions;
use App\Domain\Sales\Infrastructure\Contracts\SaleServiceContract;
use App\Domain\Sales\Infrastructure\Enums\SaleStatusesEnum;
use App\Domain\Sales\Infrastructure\Exceptions\SaleAlreadyCancelledException;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SaleService implements SaleServiceContract
{
    public function __construct(
        private SalesQueryActions $salesQueryAction,
        private SalesCommandActions $salesCommandActions,
        private SaleProductService $saleProductService,
        private ProductService $productService
    ) {
    }

    public function getSales(): Collection
    {
        return $this->salesQueryAction->getSales();
    }

    public function getSale(string $id): Sale
    {
        return $this->salesQueryAction->getSale($id);
    }

    public function newSale(array $payload): Sale
    {
        $salePayload = [
            'id' => Uuid::uuid4()->toString(),
            'amount' => 0,
            'status' => SaleStatusesEnum::Pending->value
        ];

        return DB::transaction(function () use ($salePayload, $payload) {
            $sale = $this->salesCommandActions->newSale($salePayload);

            foreach ($payload['products'] as $saleProductPayload) {

                if (!$this->productService->getProduct($saleProductPayload['product_id'])) {
                    throw new ProductNotFoundException('The product of ID ' . $saleProductPayload['product_id'] . 'does not exists');
                }

                $saleProductPayload['id'] = Uuid::uuid4()->toString();
                $saleProductPayload['sale_id'] = $sale->id;

                $this->saleProductService->newSaleProduct($saleProductPayload);
            }

            return $sale;
        });
    }

    public function cancel(string $id): Sale
    {
        $sale = $this->getSale($id);
        
        if($sale->status == SaleStatusesEnum::Cancelled->value){
            throw new SaleAlreadyCancelledException('This sale is already cancelled');
        }

        return $this->salesCommandActions->cancel($sale);
    }
}
