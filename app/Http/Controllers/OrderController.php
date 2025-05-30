<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\CartService;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(private OrderService $service, private CartService $cartService)
    {
    }

    public function index(Request $request)
    {
        $orders = $this->service->getUserOrders($request->user()->id);
        return response()->json(['data' => $orders]);
    }

    public function checkout(Request $request)
    {
        $cart = $this->cartService->getCartWithItems($request->user());
        
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }
        
        try {
            $request->validate([
                'shipping_address' => 'required|string',
                'phone_number' => 'required|string',
                'note' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            $order = $this->service->createOrderFromCart($cart, $request->shipping_address, $request->phone_number, $request->note);

            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order
            ], 201);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, 'Insufficient stock:')) {
                $stockData = str_replace('Insufficient stock: ', '', $errorMessage);
                $insufficientItems = json_decode($stockData, true);
                
                return response()->json([
                    'message' => 'Insufficient stock for some items',
                    'errors' => [
                        'stock' => $insufficientItems
                    ]
                ], 409);
            }
            
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $errorMessage
            ], 500);
        }
    }
}
