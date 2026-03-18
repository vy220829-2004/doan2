<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request): JsonResponse
    {
        // Backward compatible alias.
        return $this->add($request);
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = (int) ($validated['quantity'] ?? 1);

        if (Auth::check()) {
            $userId = (int) Auth::id();

            $cartItem = CartItem::query()->firstOrCreate(
                ['user_id' => $userId, 'product_id' => $productId],
                ['quantity' => 0]
            );

            $cartItem->quantity = (int) $cartItem->quantity + $quantity;
            $cartItem->save();

            $cartCount = (int) CartItem::query()
                ->where('user_id', $userId)
                ->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm thành công vào Giỏ hàng của bạn',
                'stored' => 'database',
                'cart_count' => $cartCount,
            ]);
        }

        // Guest users: store in session so cart can still work without DB.
        $cart = (array) $request->session()->get('cart', []);
        $cart[$productId] = (int) ($cart[$productId] ?? 0) + $quantity;
        $request->session()->put('cart', $cart);

        $cartCount = (int) array_sum($cart);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm thành công vào Giỏ hàng của bạn',
            'stored' => 'session',
            'cart_count' => $cartCount,
        ]);
    }

    public function loadMiniCart(): JsonResponse
    {
        // Backward compatible alias.
        return $this->miniCart(request());
    }

    public function miniCart(Request $request): JsonResponse
    {
        $cartLines = [];
        $cartCount = 0;
        $subtotal = 0;

        if (Auth::check()) {
            $userId = (int) Auth::id();
            $items = CartItem::query()
                ->with(['product.firstImage'])
                ->where('user_id', $userId)
                ->get();

            foreach ($items as $item) {
                if (!$item->product) {
                    continue;
                }
                $qty = (int) $item->quantity;
                $cartLines[] = [
                    'product' => $item->product,
                    'quantity' => $qty,
                ];
                $cartCount += $qty;
                $subtotal += $qty * (float) $item->product->price;
            }
        } else {
            // Guest cart stored in session: [productId => quantity]
            $cart = (array) $request->session()->get('cart', []);
            $productIds = array_values(array_filter(array_map('intval', array_keys($cart))));

            $products = $productIds
                ? Product::query()->with(['firstImage'])->whereIn('id', $productIds)->get()->keyBy('id')
                : collect();

            foreach ($cart as $productId => $qtyRaw) {
                $productId = (int) $productId;
                $qty = (int) $qtyRaw;
                if ($qty <= 0) {
                    continue;
                }
                $product = $products->get($productId);
                if (!$product) {
                    continue;
                }
                $cartLines[] = [
                    'product' => $product,
                    'quantity' => $qty,
                ];
                $cartCount += $qty;
                $subtotal += $qty * (float) $product->price;
            }
        }

        $html = view('clients.admin.layouts.components.includes.mini_cart', [
            'cartLines' => $cartLines,
            'cartCount' => $cartCount,
            'subtotal' => $subtotal,
        ])->render();

        return response()->json([
            'status' => true,
            'success' => true,
            'cart_count' => $cartCount,
            'html' => $html,
        ]);
    }

    public function removeFromCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $productId = (int) $validated['product_id'];

        if (Auth::check()) {
            $userId = (int) Auth::id();

            CartItem::query()
                ->where('user_id', $userId)
                ->where('product_id', $productId)
                ->delete();

            $cartCount = (int) CartItem::query()
                ->where('user_id', $userId)
                ->sum('quantity');

            return response()->json([
                'status' => true,
                'success' => true,
                'cart_count' => $cartCount,
            ]);
        }

        $cart = (array) $request->session()->get('cart', []);
        unset($cart[$productId]);
        $request->session()->put('cart', $cart);

        $cartCount = (int) array_sum($cart);

        return response()->json([
            'status' => true,
            'success' => true,
            'cart_count' => $cartCount,
        ]);
    }

    public function removeFormMiniCart(Request $request): JsonResponse
    {
        // Backward compatible alias.
        return $this->removeFromCart($request);
    }
    // view cart page
    public function viewCart(Request $request)
    {
        $cartItems = [];

        if (Auth::check()) {
            $userId = (int) Auth::id();

            $items = CartItem::query()
                ->with(['product.firstImage'])
                ->where('user_id', $userId)
                ->get();

            foreach ($items as $item) {
                if (!$item->product) {
                    continue;
                }

                $cartItems[] = [
                    'product_id' => (int) $item->product_id,
                    'name' => (string) $item->product->name,
                    'price' => (float) $item->product->price,
                    'quantity' => (int) $item->quantity,
                    'stock' => (int) $item->product->stock ,
                    'image_url' => (string) $item->product->image_url,
                ];
            }
        } else {
            // Guest cart is stored in session.
            // New format: [productId => quantity]
            // Legacy format: [[product_id, name, price, quantity, stock, image], ...]
            $cart = $request->session()->get('cart', []);

            $first = is_array($cart) && $cart !== [] ? reset($cart) : null;
            $isLegacyLines = is_array($first) && array_key_exists('product_id', $first);

            if ($isLegacyLines) {
                $cartItems = array_values(array_filter($cart, fn ($line) => is_array($line)));
            } else {
                $cart = (array) $cart;
                $productIds = array_values(array_filter(array_map('intval', array_keys($cart))));

                $products = $productIds
                    ? Product::query()->with(['firstImage'])->whereIn('id', $productIds)->get()->keyBy('id')
                    : collect();

                foreach ($cart as $productId => $qtyRaw) {
                    $productId = (int) $productId;
                    $qty = (int) $qtyRaw;
                    if ($qty <= 0) {
                        continue;
                    }

                    $product = $products->get($productId);
                    if (!$product) {
                        continue;
                    }

                    $cartItems[] = [
                        'product_id' => $productId,
                        'name' => (string) $product->name,
                        'price' => (float) $product->price,
                        'quantity' => $qty,
                        'stock' => (int) ($product->stock ?? 0),
                        'image_url' => (string) $product->image_url,
                    ];
                }
            }
        }

        return view('clients.admin.layouts.pages.cart', ['cartItems' => $cartItems]);
    }

    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = (int) $validated['quantity'];

        $product = Product::query()->find($productId);
        if (!$product) {
            return response()->json(['error' => 'Sản phẩm không tồn tại.'], 404);
        }

        if ($quantity > (int) ($product->stock ?? 0)) {
            return response()->json(['error' => 'Số lượng yêu cầu vượt quá tồn kho.'], 400);
        }

        if (Auth::check()) {
            $cartItem = CartItem::query()
                ->where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng của bạn.'], 404);
            }

            $cartItem->quantity = $quantity;
            $cartItem->save();
        } else {
            $cart = (array) $request->session()->get('cart', []);

            $first = $cart !== [] ? reset($cart) : null;
            $isLegacyLines = is_array($first) && array_key_exists('product_id', $first);

            if ($isLegacyLines) {
                $found = false;
                foreach ($cart as $index => $line) {
                    if (!is_array($line)) {
                        continue;
                    }
                    if ((int) ($line['product_id'] ?? 0) === $productId) {
                        $cart[$index]['quantity'] = $quantity;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng của bạn.'], 404);
                }
            } else {
                // New guest cart format: [productId => quantity]
                if (!array_key_exists($productId, $cart)) {
                    return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng của bạn.'], 404);
                }
                $cart[$productId] = $quantity;
            }

            $request->session()->put('cart', $cart);
        }

        $itemSubtotal = $quantity * (float) $product->price;
        $cartTotal = (float) $this->calculateCartTotal();
        $shippingFee = 25000;
        $grandTotal = $cartTotal + $shippingFee;

        $cartCount = null;
        if (Auth::check()) {
            $cartCount = (int) CartItem::query()->where('user_id', Auth::id())->sum('quantity');
        } else {
            $cart = (array) $request->session()->get('cart', []);
            $first = $cart !== [] ? reset($cart) : null;
            $isLegacyLines = is_array($first) && array_key_exists('product_id', $first);
            $cartCount = $isLegacyLines
                ? (int) collect($cart)->sum(fn ($item) => (int) ($item['quantity'] ?? 0))
                : (int) array_sum(array_map('intval', $cart));
        }

        return response()->json([
            'success' => true,
            'quantity' => $quantity,
            'item_subtotal' => number_format($itemSubtotal, 0, ',', '.') . 'Đ',
            'cart_total' => number_format($cartTotal, 0, ',', '.') . 'Đ',
            'cart_grand_total' => number_format($grandTotal, 0, ',', '.') . 'Đ',
            'cart_count' => $cartCount,
        ]);
    }
    // hanlde update quantity product in page cart

    public function removeCartItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $productId = (int) $validated['product_id'];

        if (Auth::check()) {
            CartItem::query()
                ->where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            $cart = (array) $request->session()->get('cart', []);

            $first = $cart !== [] ? reset($cart) : null;
            $isLegacyLines = is_array($first) && array_key_exists('product_id', $first);

            if ($isLegacyLines) {
                $cart = array_values(array_filter(
                    $cart,
                    fn ($line) => is_array($line) && (int) ($line['product_id'] ?? 0) !== $productId
                ));
            } else {
                unset($cart[$productId]);
            }

            $request->session()->put('cart', $cart);
        }

        $cartTotal = (float) $this->calculateCartTotal();
        $shippingFee = 25000;
        $grandTotal = $cartTotal + $shippingFee;

        if (Auth::check()) {
            $cartCount = (int) CartItem::query()->where('user_id', Auth::id())->sum('quantity');
        } else {
            $cart = (array) $request->session()->get('cart', []);
            $first = $cart !== [] ? reset($cart) : null;
            $isLegacyLines = is_array($first) && array_key_exists('product_id', $first);
            $cartCount = $isLegacyLines
                ? (int) collect($cart)->sum(fn ($item) => (int) ($item['quantity'] ?? 0))
                : (int) array_sum(array_map('intval', $cart));
        }

        return response()->json([
            'success' => true,
            'product_id' => $productId,
            'quantity' => 0,
            'item_subtotal' => number_format(0, 0, ',', '.') . 'Đ',
            'cart_total' => number_format($cartTotal, 0, ',', '.') . 'Đ',
            'cart_grand_total' => number_format($grandTotal, 0, ',', '.') . 'Đ',
            'cart_count' => $cartCount,
        ]);
    }


    function calculateCartTotal()
    {
        if (Auth::check())
            {
                return CartItem::where('user_id', Auth::id())
                    ->with('product')
                    ->get()
                    ->sum(fn ($item) => $item->quantity * $item->product->price);
                
            }else{
                $cart = (array) session()->get('cart', []);

                $first = $cart !== [] ? reset($cart) : null;
                $isLegacyLines = is_array($first) && array_key_exists('product_id', $first);

                if ($isLegacyLines) {
                    return (float) collect($cart)->sum(fn ($item) => (int) ($item['quantity'] ?? 0) * (float) ($item['price'] ?? 0));
                }

                // New guest cart format: [productId => quantity]
                $productIds = array_values(array_filter(array_map('intval', array_keys($cart))));
                if ($productIds === []) {
                    return 0;
                }

                $products = Product::query()->whereIn('id', $productIds)->get()->keyBy('id');

                $total = 0;
                foreach ($cart as $productId => $qtyRaw) {
                    $productId = (int) $productId;
                    $qty = (int) $qtyRaw;
                    if ($qty <= 0) {
                        continue;
                    }
                    $product = $products->get($productId);
                    if (!$product) {
                        continue;
                    }
                    $total += $qty * (float) $product->price;
                }

                return $total;

            }
    }
}
