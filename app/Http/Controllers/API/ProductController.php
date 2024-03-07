<?php

namespace App\Http\Controllers\API;

use App\Domain\Products\Application\Services\ProductService;
use App\Http\Controllers\Controller;
use Exception;
use App\Traits\HandleExceptionResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HandleExceptionResponse;

    public function __construct(private ProductService $productService)
    {
    }

    public function getProducts(): JsonResponse
    {
        try {
            $products = $this->productService->getProducts();

            if($products->count() == 0) {
                return response()->json([
                    'No product was found in the database, please try again later.'
                ], 404);
            }

            return response()->json($products);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }
}
