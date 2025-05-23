<?php

namespace App\Services;

use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;

class CartService
{
    public function __construct(protected CartRepository $cartRepository, protected ProductRepository $productRepository)
    {
    }

    public function getOrCreateCart(User $user): Cart
    {
        return $this->cartRepository->getOrCreateCart($user);
    }

    public function getCartWithItems(User $user): Cart
    {
        $cart = $this->getOrCreateCart($user);
        return $this->cartRepository->getCartWithItems($cart);
    }

    public function addItemToCart(User $user, int $productId, int $quantity): CartItem
    {
        $product = $this->productRepository->getProductById($productId);
        
        if ($product->stock < $quantity) {
            throw new \Exception('Insufficient stock available. Only ' . $product->stock . ' items left in stock.');
        }

        $cart = $this->getOrCreateCart($user);
        return $this->cartRepository->addItem($cart, $productId, $quantity);
    }

    public function updateCartItemQuantity(CartItem $cartItem, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->cartRepository->removeItem($cartItem);
        }

        if ($cartItem->product->stock < $quantity) {
            throw new \Exception('Insufficient stock available. Only ' . $cartItem->product->stock . ' items left in stock.');
        }

        return $this->cartRepository->updateItemQuantity($cartItem, $quantity);
    }

    public function removeItemFromCart(CartItem $cartItem): bool
    {
        return $this->cartRepository->removeItem($cartItem);
    }
}
