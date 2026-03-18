@extends('clients.admin.layouts.client')

@section('title', 'Đăng nhập')
@section('breadcrumb', 'Đăng nhập')

@section('content')

        <!-- LOGIN AREA START -->
        <div class="ltn__login-area pb-65">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area text-center">
                            <h1 class="section-title">Đăng nhập <br> tài khoản của bạn</h1>
                            <p>Vui lòng nhập thông tin đăng nhập để tiếp tục. <br>
                                Nếu bạn chưa có tài khoản, hãy đăng ký để trải nghiệm đầy đủ các tính năng.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="account-login-inner">
                            <form id="login-form" action="{{ route('login.post') }}" method="POST" class="ltn__form-box contact-form-box">
                                @csrf
                                <input type="email" name="email" placeholder="Email*">
                                <input type="password" name="password" placeholder="Mật khẩu*">
                                <div class="btn-wrapper mt-0">
                                    <button class="theme-btn-1 btn btn-block" type="submit">ĐĂNG NHẬP</button>
                                </div>
                                <div class="go-to-btn mt-20">
                                    <a href="{{ route('password.request') }}"><small>BẠN QUÊN MẬT KHẨU?</small></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-create text-center pt-50">
                            <h4>BẠN CHƯA CÓ TÀI KHOẢN?</h4>
                            <p>Thêm sản phẩm vào danh sách yêu thích, nhận gợi ý phù hợp <br>
                                thanh toán nhanh hơn, theo dõi đơn hàng và đăng ký thành viên.</p>
                            <div class="btn-wrapper">
                                <a href="{{ route('register') }}" class="theme-btn-1 btn black-btn">TẠO TÀI KHOẢN</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- LOGIN AREA END -->
@endsection