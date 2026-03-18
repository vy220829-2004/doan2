<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaetController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->merge(['quantity' => (int) $request->quantity]);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);
        if ($request->quantity > $product->stock) {
            return response()->json(['message' => 'Số lượng vượt quá tồn kho'], 400);
        }
        //if login then 
        if (Auth::check()){
            $cartItem = \App\Models\CartItem::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();
            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                \App\Models\CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ]);
            }
            $cartCount = \App\Models\CartItem::where('user_id', Auth::id())->count();
        }else {
            $cart = session()->get('cart', []);
            if (isset($cart[$request->product_id])) {
                $cart[$request->product_id]['quantity'] += $request->quantity;
            } else {
                $cart[$request->product_id] =
                 ['product_id' => $request->product_id,
                 'name' => $product->name,
                 'price' => $product->price,
                 'quantity' => $request->quantity,
                 'stock' => $product->stock,
                'image' => $product->image?->first()?->image ?? 'upload/products/default-product.png',
                 ];
            }
            session()->put('cart', $cart);
            $cartCount = count($cart);
        }

        return response()->json([
        'message' => true, 
        'cartCount' => $cartCount]);
    }
}