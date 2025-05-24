<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function __construct(protected Product $model)
    {
    }

    public function getPaginatedProducts(int $perPage = 10, bool $includeOutOfStock = false): LengthAwarePaginator
    {
        $query = $this->model
            ->with('images')
            ->where('status', 'active');
            
        if (!$includeOutOfStock) {
            $query->where('stock', '>', 0);
        }
        
        return $query->paginate($perPage);
    }

    public function getProductById(int $id): ?Product
    {
        return $this->model->with('images')->find($id);
    }

    public function validateStock(array $items): array
    {
        $insufficientItems = [];
        
        foreach ($items as $item) {
            $product = $this->getProductById($item['product_id']);
            
            if (!$product) {
                $insufficientItems[] = [
                    'product_id' => $item['product_id'],
                    'requested' => $item['quantity'],
                    'available' => 0,
                    'message' => 'Product not found'
                ];
                continue;
            }
            
            if ($product->stock < $item['quantity']) {
                $insufficientItems[] = [
                    'product_id' => $item['product_id'],
                    'product_name' => $product->name,
                    'requested' => $item['quantity'],
                    'available' => $product->stock,
                    'message' => "Insufficient stock for {$product->name}. Requested: {$item['quantity']}, Available: {$product->stock}"
                ];
            }
        }
        
        return $insufficientItems;
    }

    public function updateStock(array $items): bool
    {
        foreach ($items as $item) {
            $product = $this->getProductById($item['product_id']);
            
            if (!$product) {
                throw new \Exception("Product with ID {$item['product_id']} not found");
            }
            
            if ($product->stock < $item['quantity']) {
                throw new \Exception("Insufficient stock for product {$product->name}");
            }
            
            $product->decrement('stock', $item['quantity']);
        }
        
        return true;
    }
}
