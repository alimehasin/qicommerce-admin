<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;


class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index(): JsonResponse
    {
        $products = $this->productService->getPaginatedProducts();

        return response()->json($products);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        return response()->json($product);
    }
}
