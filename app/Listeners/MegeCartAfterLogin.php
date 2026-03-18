<?php

namespace App\Listeners;

use App\Models\CartItem;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MegeCartAfterLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $sessionCart = Session::get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        DB::transaction(function () use ($user, $sessionCart) {
            foreach ($sessionCart as $productId => $cartItem) {
                $productId = (int) $productId;

                // Session cart (guest) is stored as: [productId => quantity]
                // but support legacy shape too: [productId => ['quantity' => x]]
                $qty = is_array($cartItem)
                    ? (int) ($cartItem['quantity'] ?? 0)
                    : (int) $cartItem;
                if ($qty <= 0) {
                    continue;
                }

                $existingCartItem = CartItem::where('user_id', $user->id)
                    ->where('product_id', $productId)
                    ->first();

                if ($existingCartItem) {
                    $existingCartItem->quantity += $qty;
                    $existingCartItem->save();
                } else {
                    CartItem::create([
                        'user_id' => $user->id,
                        'product_id' => $productId,
                        'quantity' => $qty,
                    ]);
                }
            }
        });

        Session::forget('cart');
    }
}