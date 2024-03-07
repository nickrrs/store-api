<?php

namespace App\Http\Controllers\API;

use App\Domain\Sales\Application\Services\SaleService;
use App\Domain\Sales\Infrastructure\DTO\NewSaleOutputDTO;
use App\Domain\Sales\Infrastructure\Requests\NewSaleRequest;
use App\Http\Controllers\Controller;
use Exception;
use App\Traits\HandleExceptionResponse;
use Illuminate\Http\JsonResponse;

class SalesController extends Controller
{
    use HandleExceptionResponse;

    public function __construct(private SaleService $saleService)
    {
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
