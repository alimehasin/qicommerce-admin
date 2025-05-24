<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class OrderRepository
{
    public function __construct(private Order $model, private OrderItem $orderItemModel, private Cart $cartModel)
    {
    }

    public function getUserOrders(int $userId)
    {
        return $this->model
            ->with(['items', 'user'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        $order = $this->model->create([
            'user_id' => $data['user_id'],
            'status' => $data['status'],
            'shipping_address' => $data['shipping_address'],
            'total_amount' => $data['total_amount'],
            'phone_number' => $data['phone_number'],
            'note' => $data['note'],
        ]);

        foreach ($data['items'] as $item) {
            $this->orderItemModel->create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        $this->cartModel->where('user_id', $data['user_id'])->delete();

        return $order->load('items');
    }

    public function findById(int $id): ?Order
    {
        return $this->model->with(['items', 'user'])->find($id);
    }
}
