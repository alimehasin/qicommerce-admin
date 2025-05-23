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
        $perPage = request()->get('per_page', 10);
        $products = $this->productService->getPaginatedProducts($perPage);

        return response()->json($products);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        return response()->json($product);
    }
}
