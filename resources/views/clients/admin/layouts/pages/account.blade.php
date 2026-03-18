@extends('clients.admin.layouts.client')

@section('title', 'Tài khoản')
@section('breadcrumb', 'Tài khoản')

@section('content')
@php($user = $user ?? auth()->user())
@php($addresses = $addresses ?? collect())
@php($orders = $orders ?? collect())
<!-- WISHLIST AREA START -->
<div class="liton__wishlist-area pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- PRODUCT TAB AREA START -->
                <div class="ltn__product-tab-area">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="ltn__tab-menu-list mb-50">
                                    <div class="nav">
                                        <a class="active show" data-bs-toggle="tab"
                                            href="#liton_tab_dashboard">Bảng Điều Khiển <i class="fas fa-home"></i></a>
                                        <a data-bs-toggle="tab" href="#liton_tab_orders">Đơn Hàng <i
                                                class="fas fa-file-alt"></i></a>
                                        <a data-bs-toggle="tab" href="#liton_tab_address">Địa Chỉ <i
                                                class="fas fa-map-marker-alt"></i></a>
                                        <a data-bs-toggle="tab" href="#liton_tab_account">Chi Tiết Tài Khoản <i
                                                class="fas fa-user"></i></a>
                                        <a data-bs-toggle="tab" href="#liton_tab_password">Đổi Mật Khẩu <i
                                                class="fas fa-user"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="liton_tab_dashboard">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <p>Xin chào <strong>{{ $user?->email }}</strong> (not <strong>{{ $user?->email }}</strong>?
                                                <small><a href="{{ route('logout') }}">Đăng xuất</a></small> )
                                            </p>
                                            <p>Từ bảng điều khiển tài khoản, bạn có thể xem <span>đơn hàng gần đây</span>, quản lý
                                                <span>địa chỉ giao hàng và thanh toán</span>, và <span>chỉnh sửa mật khẩu cùng thông tin tài
                                                    khoản</span>.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="liton_tab_orders">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Đơn hàng</th>
                                                            <th>Ngày đặt</th>
                                                            <th>Trạng thái</th>
                                                            <th>Tổng tiền</th>
                                                            <th>Hành động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($orders as $order)
                                                        <tr>
                                                            <td>#{{ $order->id }}</td>
                                                            <td>{{ $order->created_at?->format('d/m/Y') }}</td>
                                                            <td>
                                                                @php($status = trim(strtolower((string) ($order->status ?? ''))))

                                                                @if($status == 'pending')
                                                                <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                                                @elseif($status == 'processing')
                                                                <span class="badge bg-primary">Đang xử lý</span>
                                                                @elseif($status == 'completed')
                                                                <span class="badge bg-success">Hoàn thành</span>
                                                                @elseif(in_array($status, ['cancelled', 'canceled', 'cancel'], true))
                                                                <span class="badge bg-danger">Đã hủy</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ number_format($order->total_price ?? 0, 0, ',', '.') }} VNĐ</td>
                                                            <td>
                                                                <a href="{{ route('order.show', $order->id) }}" class="btn btn-info btn-sm">Xem chi tiết</a>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center">Chưa có đơn hàng nào.</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="liton_tab_address">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Tên người nhận</th>
                                                            <th>Địa chỉ</th>
                                                            <th>Thành phố</th>
                                                            <th>Điện thoại</th>
                                                            <th>Mặc định</th>
                                                            <th>Hành động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($addresses as $address)
                                                        <tr>
                                                            <td>{{ $address->fullname }}</td>
                                                            <td>{{ $address->address }}</td>
                                                            <td>{{ $address->city }}</td>
                                                            <td>{{ $address->phone }}</td>
                                                            <td>
                                                                @if ($address->default)
                                                                <span class="badge bg-success">Mặc định</span>
                                                                @else
                                                                <form action="{{ route('account.update_address', $address->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn btn-effect-1 btn-warning">chọn</button>
                                                                </form>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <form action="{{ route('account.delete_address', $address->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">Xóa</button>
                                                                </form>

                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <button type="button" class="btn theme-btn-1 btn-effect-1 mt-3" data-bs-toggle="modal" data-bs-target="#addAddressModal">thêm địa chỉ mới</button>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="liton_tab_account">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <div class="ltn__form-box">
                                                <form action="{{ route('account.update') }}" method="POST" id="update-account" enctype="multipart/form-data">

                                                    @csrf

                                                    @method('PUT')

                                                    @if (session('success'))
                                                    <div class="alert alert-success">
                                                        {{ session('success') }}
                                                    </div>
                                                    @endif

                                                    @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul class="mb-0">
                                                            @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    @endif

                                                    <div class="row mb-50">
                                                        <div class="col-md-12 text-cevter mb-3">
                                                            <div class="profile-pic-container">
                                                                @php(
                                                                $avatarUrl = $user?->avatar
                                                                ? (\Illuminate\Support\Str::startsWith($user->avatar, ['http://', 'https://'])
                                                                ? $user->avatar
                                                                : (\Illuminate\Support\Str::startsWith(ltrim($user->avatar, '/'), 'storage/')
                                                                ? asset(ltrim($user->avatar, '/'))
                                                                : asset('storage/' . ltrim($user->avatar, '/'))))
                                                                : asset('assets/clients/img/others/2.png')
                                                                )
                                                                <img src="{{ $avatarUrl }}" alt="Avatar" id="preview-image" class="profile-pic">
                                                                <input type="file" name="avatar" id="avatar" accept="image/*" class="d-none">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="ltn__name">Họ và tên:</label>
                                                            <input type="text" name="name" id="ltn__name" value="{{ old('name', $user?->name) }}" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="ltn__phone">Số điện thoại:</label>
                                                            <input type="text" name="phone_number" id="ltn__phone" value="{{ old('phone_number', $user?->phone_number) }}">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="ltn__email">Email (Không được thay đổi):</label>
                                                            <input type="text" name="ltn__email" id="ltn__email" value="{{ $user?->email }}" readonly>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="ltn__address"> Địa chỉ:</label>
                                                            <input type="text" name="address" id="ltn__address" value="{{ old('address', $user?->address) }}">
                                                        </div>
                                                    </div>
                                                    <div class="btn-wrapper">
                                                        <button type="submit"
                                                            class="btn theme-btn-1 btn-effect-1 text-uppercase">Cập nhật</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="liton_tab_password">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <div class="ltn__form-box">
                                                <form action="{{ route('account.change_password') }}" method="POST" id="change-password-form">
                                                    @csrf

                                                    @if (session('success_change_password'))
                                                    <div class="alert alert-success">
                                                        {{ session('success_change_password') }}
                                                    </div>
                                                    @endif

                                                    @if ($errors->getBag('changePassword')->any())
                                                    <div class="alert alert-danger">
                                                        <ul class="mb-0">
                                                            @foreach ($errors->getBag('changePassword')->all() as $error)
                                                            <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    @endif

                                                    <div class="row mb-50">
                                                        <div class="col-md-6">
                                                            <label for="current_password">Mật khẩu hiện tại:</label>
                                                            <input type="password" name="current_password" id="current_password" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="new_password">Mật khẩu mới:</label>
                                                            <input type="password" name="new_password" id="new_password" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="confirm_new_password">Xác nhận mật khẩu mới:</label>
                                                            <input type="password" name="confirm_new_password" id="confirm_new_password" required>
                                                        </div>
                                                    </div>
                                                    <div class="btn-wrapper">
                                                        <button type="submit"
                                                            class="btn theme-btn-1 btn-effect-1 text-uppercase">Đổi mật khẩu</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    @if (session('success_change_password') || $errors->getBag('changePassword')->any())
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            var triggerEl = document.querySelector('a[data-bs-toggle="tab"][href="#liton_tab_password"]');
                                            if (!triggerEl) return;

                                            if (window.bootstrap && window.bootstrap.Tab) {
                                                window.bootstrap.Tab.getOrCreateInstance(triggerEl).show();
                                                return;
                                            }

                                            triggerEl.click();
                                        });
                                    </script>
                                    @endif

                                    <!-- Modal: Thêm địa chỉ mới -->
                                    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="addAddressModalLabel">Thêm địa chỉ mới</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('account.add_address') }}" method="POST" id="addAddressForm">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="full_name" class="form-label">Tên người dùng</label>
                                                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="address" class="form-label">Địa chỉ</label>
                                                            <input type="text" class="form-control" id="address" name="address" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="city" class="form-label">Thành phố</label>
                                                            <input type="text" class="form-control" id="city" name="city" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="phone_number" class="form-label">Số điện thoại</label>
                                                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                                                        </div>
                                                        <div class="mb-3 form-check">
                                                            <label for="is_default" class="form-label">Đặt làm địa chỉ mặc định</label>
                                                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn theme-btn-1 btn-effect-1" form="addAddressForm">Lưu địa chỉ</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection