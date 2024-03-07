<?php

namespace App\Http\Controllers\API;

use App\Domain\Sales\Application\Services\SaleService;
use App\Domain\Sales\Infrastructure\DTO\ListSaleOutputDTO;
use App\Domain\Sales\Infrastructure\DTO\ListSalesOutputDTO;
use App\Domain\Sales\Infrastructure\DTO\NewSaleOutputDTO;
use App\Domain\Sales\Infrastructure\Requests\NewSaleRequest;
use App\Http\Controllers\Controller;
use Exception;
use App\Traits\HandleExceptionResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    use HandleExceptionResponse;

    public function __construct(private SaleService $saleService)
    {
    }

    public function getSales(): JsonResponse
    {
        try {
            $sales = $this->saleService->getSales();

            if ($sales->count() == 0) {
                return response()->json([
                    'No sale was found in the database, please try again later.'
                ], 422);
            }

            $outputDTO = ListSalesOutputDTO::fromArray($sales->toArray());

            return response()->json($outputDTO->response());
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    public function getSale(Request $request): JsonResponse
    {
        try {
            $sale = $this->saleService->getSale($request->id);
            
            $outputDTO = ListSaleOutputDTO::fromArray($sale->toArray());

            return response()->json($outputDTO->response());
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    public function newSale(NewSaleRequest $request): JsonResponse
    {
        try {
            $sale = $this->saleService->newSale($request->validated());

            $outputDTO = NewSaleOutputDTO::fromArray($sale->toArray());

            return response()->json($outputDTO->response());
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }
}
