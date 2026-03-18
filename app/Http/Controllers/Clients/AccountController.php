<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $addresses = ShippingAddress::where('user_id', Auth::id())->get();
        $orders = Order::where('user_id', $user->id)->latest()->get();

        return view('clients.admin.layouts.pages.account', compact('user', 'addresses', 'orders'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('uploads/users', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name = $validated['name'];
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->save();

        $message = 'Cập nhật thông tin tài khoản thành công.';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            ]);
        }

        return back()->with('success', $message);
    }

    public function changePassword(Request $request)
    {
        $request->validateWithBag('changePassword', [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|same:new_password',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'confirm_new_password.required' => 'Vui lòng xác nhận mật khẩu mới.',
            'confirm_new_password.same' => 'Xác nhận mật khẩu không khớp.'
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            $message = 'Mật khẩu hiện tại không đúng.';

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => [
                        'current_password' => $message,
                    ],
                ], 422);
            }

            return back()->withErrors([
                'current_password' => $message,
            ], 'changePassword');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        $message = 'Mật khẩu đã được cập nhật thành công.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success_change_password', $message);
    }

    public function addAddress(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
        ]);

        // If the new address is set as default, unset other default addresses
        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            ShippingAddress::where('user_id', Auth::id())->update(['default' => 0]);
        }

        ShippingAddress::create([
            'user_id' => Auth::id(),
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone_number'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'default' => $isDefault ? 1 : 0,
        ]);

        return back()->with('success', 'Địa chỉ đã được thêm thành công.');
    }

    public function updatePrimaryAddress($id){
        $address = ShippingAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Unset other default addresses
        ShippingAddress::where('user_id', Auth::id())->update(['default' => 0]);

        // Set the selected address as default
        $address->update(['default' => 1]);

        return back()->with('success', 'Địa chỉ đã được cập nhật thành công.');
    }

    public function deleteAddress($id)
    {
        $address = ShippingAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $wasDefault = (bool) $address->default;
        $address->delete();

        if ($wasDefault) {
            $newDefault = ShippingAddress::where('user_id', Auth::id())->first();
            if ($newDefault) {
                ShippingAddress::where('user_id', Auth::id())->update(['default' => 0]);
                $newDefault->update(['default' => 1]);
            }
        }

        return back()->with('success', 'Địa chỉ đã được xóa thành công.');
    }

}
