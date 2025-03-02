<?php

namespace App\Repositories\V1;

use App\Models\V1\Cart;
use App\Models\V1\Product;

class CartRepository
{

    public function getCartItems()
    {
        $cartItems = Cart::where('user_id', auth('sanctum')->user()->id)->get(['product_id', 'quantity']);
        $products = Product::whereIn('id', $cartItems->pluck('product_id'))->with('primaryImage')->get();
        $cartQuantities = $cartItems->pluck('quantity', 'product_id'); // Key: product_id, Value: quantity

        foreach ($products as $product) {
            $product->stock = $product->quantity;
            $product->quantity = $cartQuantities[$product->id] ?? 0;
        }

        return $products;
    }

    public function addToCart($request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1); // Default to 1
        $product = Product::findOrFail($productId);

        $cartItem = Cart::where('product_id', $productId)
            ->where('user_id', auth('sanctum')->user()->id)
            ->first();

        if ($cartItem) {
            return $this->updateCartItem($request, $product);
        }

        if ($product->quantity < $quantity) {
            throw new \Exception('Not enough stock!', 400);
        }

        return Cart::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $productId,
                'quantity' => $quantity
            ],
        );
    }

    public function updateCartItem($request, $product)
    {
        $cartItem = Cart::where('product_id', $product->id)
            ->where('user_id', auth('sanctum')->user()->id)
            ->first();

        if (!$cartItem) {
            throw new \Exception('Product not found!', 404);
        }

        if ($request->input('quantity') > $product->quantity) {
            throw new \Exception('Not enough stock!', 400);
        }

        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();

        return $cartItem;
    }

    public function deleteCartItem($product)
    {
        $cartItem = Cart::where('product_id', $product->id)
            ->where('user_id', auth('sanctum')->user()->id)
            ->first();

        if (!$cartItem) {
            throw new \Exception('Product not found!', 404);
        }

        $cartItem->delete();

        return $cartItem;
    }
}
