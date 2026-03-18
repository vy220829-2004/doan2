<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy từ khóa người dùng nhập vào từ request (tên input là 'keyword')
        $keyword = $request->input('keyword');

        // 2. Kiểm tra nếu không có từ khóa nào được nhập
        if (!$keyword) {
            // Quay lại trang trước đó kèm theo thông báo lỗi
            return redirect()->back()->with('error', 'Vui lòng nhập từ khóa tìm kiếm.');
        }

        // 3. Truy vấn database để tìm sản phẩm
        // Dùng toán tử LIKE để tìm các sản phẩm có 'name' hoặc 'description' chứa từ khóa
        $products = Product::where('name', 'like', '%' . $keyword . '%')
            ->orWhere('description', 'like', '%' . $keyword . '%')
            ->paginate(12)
            ->withQueryString(); // Giữ keyword khi chuyển trang

        // 4. Trả về view kết quả tìm kiếm cùng với biến $products
        return view('clients.admin.layouts.pages.products-search', compact('products', 'keyword'));
    }
}
