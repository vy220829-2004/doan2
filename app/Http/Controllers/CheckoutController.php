<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $addresses = ShippingAddress::where('user_id', $user->id)->get();

        if ($addresses->isEmpty()) {
            return redirect()->route('account')
                ->with('error', 'Vui lòng thêm địa chỉ giao hàng trước khi thanh toán.');
        }
        $cartItem = CartItem::where('user_id', $user->id)->with('product')->get();
        $totalPrice = $cartItem->sum(fn($item) => $item->quantity * $item->product->price  );
        $defaultAddress = $addresses->firstWhere('default', true) ?? $addresses->first();
        $selectedAddressId = $request->input('address_id');
        $selectedAddress = null;

        if ($selectedAddressId) {
            $selectedAddress = $addresses->firstWhere('id', (int) $selectedAddressId);
        }

        $selectedAddress = $selectedAddress ?? $defaultAddress;

        return view('clients.admin.layouts.pages.checkout', compact('addresses', 'defaultAddress', 'selectedAddress', 'cartItem', 'totalPrice'));
    }

    public function placeOrder(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'address_id' => ['required', 'integer'],
            'payment_method' => ['required', 'in:cash,bank_transfer'],
        ]);

        $shippingAddress = ShippingAddress::query()
            ->where('user_id', $user->id)
            ->where('id', $validated['address_id'])
            ->first();

        if (!$shippingAddress) {
            return redirect()->route('checkout')
                ->with('error', 'Địa chỉ giao hàng không hợp lệ.');
        }

        $cartItems = CartItem::query()
            ->where('user_id', $user->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $shippingFee = 25000;
        $subtotal = $cartItems->sum(function ($item) {
            if (!$item->product) {
                return 0;
            }

            return $item->quantity * $item->product->price;
        });

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $shippingAddress->id,
                'total_price' => $subtotal + $shippingFee,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                if (!$item->product) {
                    throw new \RuntimeException('Sản phẩm trong giỏ hàng không tồn tại.');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $order->total_price,
                'status' => 'pending',
                'paid_at' => null,
            ]);

            CartItem::where('user_id', $user->id)->delete();

            DB::commit();
            return redirect()->route('account')->with('success', 'Đặt hàng thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Lỗi khi đặt hàng: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('checkout')->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau.');
        }
    }
}
