@extends('clients.admin.layouts.client')

@section('title', 'Quên mật khẩu')
@section('breadcrumb', 'Quên mật khẩu')

@section('content')
	<div class="ltn__login-area pb-65">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="section-title-area text-center">
						<h1 class="section-title">Quên mật khẩu</h1>
						<p>Nhập email của bạn để nhận link đặt lại mật khẩu.</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 offset-lg-3">
					<div class="account-login-inner">
						<form action="{{ route('password.email') }}" method="POST" class="ltn__form-box contact-form-box">
							@csrf
							<input type="email" name="email" value="{{ old('email') }}" placeholder="Email*" required>
							<div class="btn-wrapper mt-0">
								<button class="theme-btn-1 btn btn-block" type="submit">GỬI LINK</button>
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