@extends('clients.admin.layouts.client')

@section('title', 'Liên hệ')
@section('breadcrumb', 'Liên hệ')

@section('content')
<!-- CONTACT ADDRESS AREA START -->
<div class="ltn__contact-address-area mb-90">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                    <div class="ltn__contact-address-icon">
                        <img src="{{ asset('assets/clients/img/icons/10.png') }}" alt="Icon Image">
                    </div>
                    <h3>Địa chỉ Email</h3>
                    <p>truminhvy2004@gmail.com <br>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                    <div class="ltn__contact-address-icon">
                        <img src="{{ asset('assets/clients/img/icons/11.png') }}" alt="Icon Image">
                    </div>
                    <h3>Số điện thoại</h3>
                    <p>+091-643-1529 <br> 
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                    <div class="ltn__contact-address-icon">
                        <img src="{{ asset('assets/clients/img/icons/12.png') }}" alt="Icon Image">
                    </div>
                    <h3>Địa chỉ</h3>
                    <p>số nhà 42/Thới Bình/Thốt Nốt, Cần Thơ <br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CONTACT ADDRESS AREA END -->

<!-- CONTACT MESSAGE AREA START -->
<div class="ltn__contact-message-area mb-120 mb--100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__form-box contact-form-box box-shadow white-bg">
                    <h4 class="title-2">Nhận báo giá</h4>
                    <form id="contact-form"
                        action="{{ route('contact.send') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-item input-item-name ltn__custom-icon">
                                    <input type="text" name="full_name" placeholder="Họ và tên" required>
                                </div>
                            </div>                            
                            <div class="col-md-6">
                                <div class="input-item input-item-phone ltn__custom-icon">
                                    <input type="text" name="phone_number" placeholder="Số điện thoại" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-item input-item-email ltn__custom-icon">
                                    <input type="email" name="email" placeholder="Địa chỉ email" required>
                                </div>
                            </div>

                        </div>
                        <div class="input-item input-item-textarea ltn__custom-icon">
                            <textarea name="message" placeholder="Nhập tin nhắn" required></textarea>
                        </div>
                        <div class="btn-wrapper mt-0">
                            <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit">Gửi</button>
                        </div>
                        <p class="form-messege mb-0 mt-20"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CONTACT MESSAGE AREA END -->

<!-- GOOGLE MAP AREA START -->
<div class="google-map mb-120">

    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3925.8642002377974!2d105.52772551036676!3d10.272524068244232!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x310a771f8cd88491%3A0xadca71fe0e72dcee!2sThoi%20binh!5e0!3m2!1svi!2s!4v1773685817459!5m2!1svi!2s"
        style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

</div>
<!-- GOOGLE MAP AREA END -->

@endsection