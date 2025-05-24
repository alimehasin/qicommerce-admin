<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user) {
            $orderCount = fake()->numberBetween(2, 5);

            for ($i = 0; $i < $orderCount; $i++) {
                $order = Order::factory()->create([
                    'user_id' => $user->id,
                ]);

                $itemCount = fake()->numberBetween(2, 4);
                $totalAmount = 0;

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = fake()->numberBetween(1, 3);
                    $price = $product->price;

                    $order->items()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);

                    $totalAmount += $price * $quantity;
                }

                // Update order total amount
                $order->update(['total_amount' => $totalAmount]);
            }
        }
    }
}
