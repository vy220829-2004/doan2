<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wishlists = Wishlist::with('product')->where('user_id', Auth::id())->get();

        return view('clients.admin.layouts.pages.wishlist', compact('wishlists'));
    }

    public function remove(Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'product_id' => ['required', 'integer', 'min:1'],
        ]);

        $deleted = Wishlist::query()
            ->where('user_id', Auth::id())
            ->where('product_id', (int) $data['product_id'])
            ->delete();

        if ($deleted) {
            return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích.');
        }

        return back()->with('error', 'Không tìm thấy sản phẩm trong danh sách yêu thích.');
    }

    public function addToCartAndRedirect(Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = (int) ($validated['quantity'] ?? 1);

        $product = Product::query()->select(['id', 'slug'])->find($productId);
        if (!$product) {
            return back()->with('error', 'Sản phẩm không tồn tại.');
        }

        $userId = (int) Auth::id();
        $cartItem = CartItem::query()->firstOrCreate(
            ['user_id' => $userId, 'product_id' => $productId],
            ['quantity' => 0]
        );

        $cartItem->quantity = (int) $cartItem->quantity + $quantity;
        $cartItem->save();

        return redirect()
            ->route('products.detail', $product->slug)
            ->with('success', 'Đã thêm vào giỏ hàng.');
    }

    public function addToWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Vui lòng đăng nhập để thêm vào danh sách yêu thích.',
            ], 401);
        }

        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        // Kiểm tra xem sản phẩm đã có trong wishlist của user này chưa
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $data['product_id'])
            ->first();

        if (!$exists) {
            // Nếu chưa có thì tiến hành thêm mới
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $data['product_id'],
            ]);
        }

        // Trả về JSON để JS nhận diện là thành công
        return response()->json([
            'status' => true,
        ]);
    }
}