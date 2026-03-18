<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('clients.admin.layouts.pages.contact');
    }

    public function sendContact(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|numeric|digits_between:10,11',
            'message' => 'required|string',
        ], [
            'full_name.required' => 'Họ và tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'phone_number.required' => 'Số điện thoại là bắt buộc.',
            'phone_number.numeric' => 'Số điện thoại phải là số.',
            'phone_number.digits_between' => 'Số điện thoại từ 10 - 11 số.',
            'message.required' => 'Tin nhắn là bắt buộc.'
        ]);

        try {
            // Lưu vào DB (Sử dụng Model Contact)
            Contact::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'message' => $request->message,
            ]);

            return redirect()->back()->with('success', 'Gửi thành công! Quản trị viên sẽ sớm liên hệ với bạn.');
        } catch (\Exception $e) {
            // Trả về thông báo lỗi nếu có sự cố
            return redirect()->back()->with('error', 'Gửi thất bại! Vui lòng thử lại sau.');
        }
    }
}