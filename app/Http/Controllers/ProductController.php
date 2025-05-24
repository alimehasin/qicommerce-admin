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
        $includeOutOfStock = request()->boolean('includeOutOfStock');
        $sortBy = request()->get('sort_by');
        $sortDirection = request()->get('sort_direction', 'asc');

        // Validate sorting parameters
        $allowedSortFields = ['name', 'price'];
        $allowedSortDirections = ['asc', 'desc'];
        
        if ($sortBy && !in_array($sortBy, $allowedSortFields)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid sort field. Allowed fields: ' . implode(', ', $allowedSortFields)
            ], 400);
        }
        
        if (!in_array(strtolower($sortDirection), $allowedSortDirections)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid sort direction. Allowed directions: ' . implode(', ', $allowedSortDirections)
            ], 400);
        }

        $products = $this->productService->getPaginatedProducts($perPage, $includeOutOfStock, $sortBy, $sortDirection);
        

        return response()->json($products);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        return response()->json($product);
    }
}
