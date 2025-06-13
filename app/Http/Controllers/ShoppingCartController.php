<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'product_id' => 'required|string',
            'amount' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        $cartItem = ShoppingCart::create($validated);

        return response()->json([
            'message' => 'Item added to shopping cart',
            'data' => $cartItem
        ], 201);
    }

    public function index($userId)
    {
        $cartItems = ShoppingCart::where('user_id', $userId)->get();

        return response()->json([
            'data' => $cartItems
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        $cartItem = ShoppingCart::findOrFail($id);
        $cartItem->update($validated);

        return response()->json([
            'message' => 'Item updated successfully',
            'data' => $cartItem
        ]);
    }

    public function destroy($id)
    {
        $cartItem = ShoppingCart::findOrFail($id);
        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from shopping cart'
        ]);
    }
    public function allCarts(){

        $cartItems = ShoppingCart::all();

        return response()->json($cartItems);
    }
    public function getCartItem($userId, $productId)
    {
    try {
        $cartItem = ShoppingCart::where('user_id', $userId)
                                ->where('product_id', $productId)
                                ->first();
        
        if (!$cartItem) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }
        
        return response()->json($cartItem, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
    }
    }

}
