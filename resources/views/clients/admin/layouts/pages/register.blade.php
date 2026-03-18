@extends('clients.admin.layouts.client')

@section('title', 'Đăng ký')
@section('breadcrumb', 'Đăng ký')

@section('content')

	<!-- LOGIN AREA START (Register) -->
<div class="ltn__login-area pb-110">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area text-center">
                    <h1 class="section-title">Đăng ký <br>Tài khoản của bạn</h1>
                    <p>Vui lòng điền đầy đủ thông tin để tạo tài khoản mới. <br>
                        Sau khi đăng ký, bạn có thể đăng nhập và sử dụng các tính năng của hệ thống.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="account-login-inner">
                    <form id="register-form" action="{{ route('register.post') }}" method="POST" class="ltn__form-box contact-form-box">
                        @csrf
                        <input type="text" name="firstname" placeholder="Họ">
                        <input type="text" name="lastname" placeholder="Tên">
                        <input type="email" name="email" placeholder="Email*">
                        <input type="password" name="password" placeholder="Mật khẩu*">
                        <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu*">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="checkbox1" value="1">
                            Tôi đồng ý cho phép hệ thống xử lý dữ liệu cá nhân của tôi để gửi thông tin/ưu đãi phù hợp
                            theo biểu mẫu chấp thuận và chính sách quyền riêng tư.
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="checkbox2" value="1">
                            Bằng việc nhấn "Tạo tài khoản", tôi đồng ý với chính sách quyền riêng tư.
                        </label>
                        <div class="btn-wrapper">
                            <button class="theme-btn-1 btn reverse-color btn-block" type="submit">TẠO
                                TÀI KHOẢN</button>
                        </div>
                    </form>
                    <div class="by-agree text-center">
                        <p>Khi tạo tài khoản, bạn đồng ý với:</p>
                        <p><a href="#">ĐIỀU KHOẢN SỬ DỤNG &nbsp; &nbsp; | &nbsp; &nbsp; CHÍNH SÁCH BẢO MẬT</a></p>
                        <div class="go-to-btn mt-50">
                            <a href="{{ route('login') }}">BẠN ĐÃ CÓ TÀI KHOẢN?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- LOGIN AREA END -->
@endsection
    