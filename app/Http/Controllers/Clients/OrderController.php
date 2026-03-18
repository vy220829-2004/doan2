<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function showOrder($id)
    {
        // Lấy thông tin đơn hàng kèm theo các bảng liên quan: orderItems (sản phẩm), user, shippingAddress, payment
        $order = Order::with(['orderItems.product', 'user', 'shippingAddress', 'payment'])->findOrFail($id);
        
        // Trả dữ liệu ra file view
        return view('clients.admin.layouts.pages.order-detail', compact('order'));
    }
}