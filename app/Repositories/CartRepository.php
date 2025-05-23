<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;

class CartRepository
{
    public function __construct(protected Cart $cartModel, protected CartItem $cartItemModel) {
    }

    public function getOrCreateCart(User $user): Cart
    {
        return $user->cart ?? $this->cartModel->create(['user_id' => $user->id]);
    }

    public function getCartWithItems(Cart $cart): Cart
    {
        return $cart->load(['items.product']);
    }

    public function addItem(Cart $cart, int $productId, int $quantity): CartItem
    {
        $cartItem = $cart->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
            return $cartItem;
        }

        return $this->cartItemModel->create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }

    public function updateItemQuantity(CartItem $cartItem, int $quantity): bool
    {
        return $cartItem->update(['quantity' => $quantity]);
    }

    public function removeItem(CartItem $cartItem): bool
    {
        return $cartItem->delete();
    }
}
