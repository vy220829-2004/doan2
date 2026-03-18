@extends('clients.admin.layouts.client')

@section('title', 'Đặt hàng')
@section('breadcrumb', 'Đặt hàng')

@section('content')
<!-- WISHLIST AREA START -->
<div class="ltn__checkout-area mb-105">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__checkout-inner">
                        <div class="ltn__checkout-single-content mt-50">
                            <h4 class="title-2">Chi tiết thanh toán</h4>
                            <div class="select-address">
                                <div>
                                    <h6>
                                        Chọn địa chỉ khác
                                    </h6>
                                </div>
                                <div>
                                    <select name = "address_id" id="list_address" class="input-item">
                                        @foreach($addresses as $address)
                                            <option
                                                value="{{ $address->id }}"
                                                {{ ($selectedAddress && $selectedAddress->id === $address->id) ? 'selected' : '' }}
                                                data-full-name="{{ e($address->full_name) }}"
                                                data-phone="{{ e($address->phone) }}"
                                                data-address="{{ e($address->address) }}"
                                                data-city="{{ e($address->city) }}"
                                            >
                                                {{ $address->full_name }} - {{ $address->address}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                    <div>
                                        <a href="{{ route('account.addresses') }}" class="btn theme-btn-1 btn-effect-1 text-uppercase">Thêm địa chỉ mới</a>
                                    </div>
                                </div>
                            <div class="ltn__checkout-single-content-info">
                                <h6>Thông tin cá nhân</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-item input-item-name ltn__custom-icon">
                                            <input id="checkout_full_name" type="text" name="name" placeholder="Họ và tên" value="{{ $selectedAddress ? $selectedAddress->full_name : '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-item-phone ltn__custom-icon">
                                            <input id="checkout_phone" type="text" name="phone" placeholder="Số điện thoại" value="{{ $selectedAddress ? $selectedAddress->phone : '' }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <h6>Địa chỉ</h6>
                                        <div class="input-item">
                                            <input id="checkout_address" type="text" name="address" placeholder="Số nhà và tên đường" value="{{ $selectedAddress ? $selectedAddress->address : '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <h6>Thành phố</h6>
                                        <div class="input-item">
                                            <input id="checkout_city" type="text" name="city" placeholder="Thành phố" value="{{ $selectedAddress ? $selectedAddress->city : '' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="ltn__checkout-payment-method mt-50">
                                    <h4 class="title-2">Phương thức thanh toán</h4>
                                    <form action="{{ route('checkout.placeOrder') }}" method="POST">
                                    @csrf
                                        <input id="checkout_address_id" type="hidden" name="address_id" value="{{ $selectedAddress ? $selectedAddress->id : $defaultAddress->id }}">
                                    <div id="checkout_payment">
                                        <div class="card">
                                            <h5 class="ltn__card-title">
                                                <label class="checkout-payment-option" for="payment_method_cash">
                                                    <span class="checkout-payment-left">
                                                        <input id="payment_method_cash" type="radio" name="payment_method" value="cash" checked>
                                                        <span>Thanh toán khi nhận hàng</span>
                                                    </span>
                                                    <img src="{{ asset('assets/clients/img/icons/cash.png') }}" alt="#">
                                                </label>
                                            </h5>
                                        </div>

                                        <div class="card">
                                            <h5 class="ltn__card-title">
                                                <label class="checkout-payment-option" for="payment_method_bank_transfer">
                                                    <span class="checkout-payment-left">
                                                        <input id="payment_method_bank_transfer" type="radio" name="payment_method" value="bank_transfer">
                                                        <span>Chuyển khoản ATM (QR)</span>
                                                    </span>
                                                </label>
                                            </h5>

                                            <div id="bank_transfer_details" class="ltn__payment-note mt-20" style="display: none;">
                                                <p class="mb-10">Quét mã QR để chuyển khoản. Nội dung chuyển khoản: <strong>Mã đơn hàng</strong> (sau khi đặt hàng).</p>
                                                <div>
                                                    @php
                                                        $qrJpgPath = 'assets/clients/img/qr/vietqr-mb.jpg';
                                                        $qrPngPath = 'assets/clients/img/qr/vietqr-mb.png';

                                                        if (file_exists(public_path($qrJpgPath))) {
                                                            $qrSrc = asset($qrJpgPath);
                                                        } elseif (file_exists(public_path($qrPngPath))) {
                                                            $qrSrc = asset($qrPngPath);
                                                        } else {
                                                            $qrSrc = asset('assets/clients/img/qr/vietqr-mb-placeholder.svg');
                                                        }
                                                    @endphp
                                                    <img
                                                        src="{{ $qrSrc }}"
                                                        alt="QR chuyển khoản"
                                                        style="max-width: 260px; width: 100%; height: auto; border: 1px solid #eee;"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ltn__payment-note mt-30 mb-30">
                                        <p>Dữ liệu cá nhân của bạn sẽ được sử dụng để xử lý đơn hàng, hỗ trợ trải nghiệm của bạn
                                            trên website này và cho các mục đích khác được mô tả trong chính sách bảo mật của chúng tôi.</p>
                                    </div> 

                                    <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit" id="order_button">Đặt hàng</button>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="shoping-cart-total mt-50">
                                    <h4 class="title-2">Tổng sản phẩm</h4>
                                    <table class="table">
                                        <tbody>
                                        @foreach ($cartItem as $item)
                                            <tr>
                                            <td>{{$item->product->name}}<strong>× {{$item->quantity}}</strong></td>
                                            <td>{{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}Đ</td>
                                            </tr>
                                        @endforeach
                                            <tr>
                                                <td>Phí vận chuyển</td>
                                                <td>{{ number_format(25000, 0, ',', '.') }}Đ</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tổng tiền</strong></td>
                                                <td><strong class="totablPrice_checkout">{{ number_format($totalPrice + 25000, 0, ',', '.') }}Đ</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- WISHLIST AREA START -->

<script>
    (function () {
        function setValue(id, value) {
            var el = document.getElementById(id);
            if (!el) return;
            el.value = value || '';
        }

        function updateFromSelectedOption() {
            var select = document.getElementById('list_address');
            if (!select) return;

            var option = select.options[select.selectedIndex];
            if (!option) return;

            var hiddenAddressId = document.getElementById('checkout_address_id');
            if (hiddenAddressId) {
                hiddenAddressId.value = option.value;
            }

            setValue('checkout_full_name', option.dataset.fullName);
            setValue('checkout_phone', option.dataset.phone);
            setValue('checkout_address', option.dataset.address);
            setValue('checkout_city', option.dataset.city);
        }

        function bind() {
            var select = document.getElementById('list_address');
            if (!select) return;

            // Native listener (works when change is a real DOM event)
            select.addEventListener('change', updateFromSelectedOption);

            // jQuery listener (needed because niceSelect triggers jQuery change)
            if (window.jQuery) {
                window.jQuery(select).on('change', updateFromSelectedOption);
            }

            // Initial sync (after plugins like niceSelect initialize)
            updateFromSelectedOption();
            window.setTimeout(updateFromSelectedOption, 0);
            window.setTimeout(updateFromSelectedOption, 50);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', bind);
        } else {
            bind();
        }
    })();
</script>

<script>
    (function () {
        function updatePaymentDetails() {
            var bankTransferRadio = document.getElementById('payment_method_bank_transfer');
            var bankTransferDetails = document.getElementById('bank_transfer_details');

            if (!bankTransferRadio || !bankTransferDetails) return;

            bankTransferDetails.style.display = bankTransferRadio.checked ? 'block' : 'none';
        }

        function bind() {
            var radios = document.querySelectorAll('input[name="payment_method"]');
            radios.forEach(function (radio) {
                radio.addEventListener('change', updatePaymentDetails);
            });

            updatePaymentDetails();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', bind);
        } else {
            bind();
        }
    })();
</script>
@endsection