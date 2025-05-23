<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function __construct(protected Product $model)
    {
    }

    public function getPaginatedProducts(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('images')->paginate($perPage);
    }

    public function getProductById(int $id): ?Product
    {
        return $this->model->with('images')->findById($id);
    }
}
