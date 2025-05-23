<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    public function __construct(protected ProductRepository $repository)
    {
    }

    public function getPaginatedProducts(int $perPage = 10)
    {
        $products = $this->repository->getPaginatedProducts($perPage);

        return [
            'status' => 'success',
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'links' => [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl(),
            ]
        ];
    }

    public function getProductById(int $id)
    {
        return $this->repository->getProductById($id);
    }
}
