<?php

namespace App\Traits;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait HandleExceptionResponse
{
    private function handleException(\Exception $exception): JsonResponse
    {
        $code = $exception instanceof HttpException ? $exception->getStatusCode() : 500;
        $message = $exception->getMessage();

        if ($exception instanceof QueryException) {
            $message = $exception->getMessage() ?? 'A query excepetion has ocurred';
        }

        return response()->json(['errors' => ['message' => $message]], $code);
    }
}
