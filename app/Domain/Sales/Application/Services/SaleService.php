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

        if ($sale->status == SaleStatusesEnum::Cancelled->value) {
            throw new SaleAlreadyCancelledException('This sale is already cancelled');
        }

        return $this->salesCommandActions->cancel($sale);
    }

    public function addProduct(array $payload, string $id): Sale
    {
        $sale = $this->getSale($id);

        if ($sale->status == SaleStatusesEnum::Cancelled->value || $sale->status == SaleStatusesEnum::Completed->value) {
            throw new SaleAlreadyCancelledException('You cant add new products to this sale');
        }

        DB::transaction(function () use ($sale, $payload) {
            $existingProducts = [];
            foreach ($sale->toArray()['products'] as $product) {
                $existingProducts[$product['id']] = $product;
            }

            foreach ($payload['products'] as $saleProduct) {
                if (array_key_exists($saleProduct['product_id'], $existingProducts)) {
                    $retrievedSaleProduct = $this->saleProductService->getSaleProductByProduct($saleProduct['product_id']);

                    $this->saleProductService->updateSaleProduct($retrievedSaleProduct, [
                        'amount' => $retrievedSaleProduct->amount + $saleProduct['amount']
                    ]);
                } else {
                    $this->saleProductService->newSaleProduct([
                        'id' => Uuid::uuid4()->toString(),
                        'amount' => $saleProduct['amount'],
                        'sale_id' => $sale->id,
                        'product_id' => $saleProduct['product_id']
                    ]);
                }
            }
        });

        return $sale->refresh();
    }
}
