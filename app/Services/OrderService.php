<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;

class OrderService
{
    public function __construct(
        private OrderRepository $repository,
        private ProductRepository $productRepository
    ) {
    }

    public function getUserOrders(int $userId)
    {
        return $this->repository->getUserOrders($userId);
    }

    public function createOrderFromCart(Cart $cart, string $shippingAddress, string $phoneNumber, string $note)
    {
        return DB::transaction(function () use ($cart, $shippingAddress, $phoneNumber, $note) {
            $cartItems = $cart->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ];
            })->toArray();

            $insufficientItems = $this->productRepository->validateStock($cartItems);
            
            if (!empty($insufficientItems)) {
                throw new \Exception('Insufficient stock: ' . json_encode($insufficientItems));
            }

            $this->productRepository->updateStock($cartItems);

            $totalAmount = $cart->items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $order = $this->repository->create([
                'user_id' => $cart->user_id,
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
                'total_amount' => $totalAmount,
                'phone_number' => $phoneNumber,
                'note' => $note,
                'items' => $cart->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ];
                }),
            ]);

            return $order;
        });
    }
}