<?php

namespace App\Traits;

use App\Domain\Products\Infrastructure\Exceptions\ProductNotFoundException;
use App\Domain\Sales\Infrastructure\Exceptions\InsuficientProductsException;
use App\Domain\Sales\Infrastructure\Exceptions\SaleAlreadyCancelledException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait HandleExceptionResponse
{
    private function handleException(\Exception $exception): JsonResponse
    {
        $code = $exception instanceof HttpException ? $exception->getStatusCode() : 500;
        $message = $exception->getMessage();

        if($exception instanceof QueryException) {
            $message = $exception->getMessage() ?? 'A query excepetion has ocurred';
        }

        if($exception instanceof ProductNotFoundException) {
            $message = $exception->getMessage() ?? 'A product not found error has ocurred';
            $code = 422;
        }

        if($exception instanceof InsuficientProductsException) {
            $message = $exception->getMessage() ?? 'Insuficient quantity of products in the operation';
            $code = 422;
        }

        if($exception instanceof SaleAlreadyCancelledException) {
            $message = $exception->getMessage() ?? 'This sale is already cancelled';
            $code = 422;
        }

        return response()->json(['errors' => ['message' => $message]], $code);
    }
}
