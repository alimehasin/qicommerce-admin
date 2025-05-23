<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(protected CartService $service)
    {
    }

    public function index(Request $request)
    {
        $cart = $this->service->getCartWithItems($request->user());

        return response()->json($cart);
    }

    public function addItem(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $cartItem = $this->service->addItemToCart(
                $request->user(),
                $request->product_id,
                $request->quantity
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Item added to cart successfully',
                'data' => $cartItem
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function updateItem(Request $request, CartItem $cartItem)
    
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:0'
            ]);    

            $success = $this->service->updateCartItemQuantity(
                $cartItem,
                $request->quantity
            );

            if (!$success) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update cart item'
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Cart item updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function removeItem(CartItem $cartItem)
    {
        $success = $this->service->removeItemFromCart($cartItem);

        if (!$success) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to remove item from cart'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed from cart successfully'
        ]);
    }
}
