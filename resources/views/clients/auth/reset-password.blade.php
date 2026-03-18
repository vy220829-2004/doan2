@extends('clients.admin.layouts.client')

@section('title', 'Đặt lại mật khẩu')
@section('breadcrumb', 'Đặt lại mật khẩu')

@section('content')
	<div class="ltn__login-area pb-65">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="section-title-area text-center">
						<h1 class="section-title">Đặt lại mật khẩu</h1>
						<p>Vui lòng nhập email và mật khẩu mới của bạn.</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 offset-lg-3">
					<div class="account-login-inner">
						<form action="{{ route('password.update') }}" method="POST" class="ltn__form-box contact-form-box" id ="reset-password-form">
							@csrf
							<input type="hidden" name="token" value="{{ $token }}">
							<input type="email" name="email" value="{{ old('email', $email ?? '') }}" placeholder="Email*" required>
							<input type="password" name="password" placeholder="Mật khẩu mới*" required>
							<input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu mới*" required>
							<div class="btn-wrapper mt-0">
								<button class="theme-btn-1 btn btn-block" type="submit">CẬP NHẬT MẬT KHẨU</button>
							</div>
							<div class="go-to-btn mt-20 text-center">
								<a href="{{ route('login') }}"><small>QUAY LẠI ĐĂNG NHẬP</small></a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection