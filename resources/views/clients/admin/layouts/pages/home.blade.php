@extends('clients.admin.layouts.client_home')

@section('title', 'Trang chủ')

@section('content')

<!-- SLIDER AREA START (slider-3) -->
<div class="ltn__slider-area ltn__slider-3  section-bg-1">
    <div class="ltn__slide-one-active slick-slide-arrow-1 slick-slide-dots-1">
        <!-- ltn__slide-item -->
        <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3 ltn__slide-item-3-normal bg-image"
            data-bg="{{ asset(path: 'assets/clients/img/slider/13.jpg') }}">
            <div class="ltn__slide-item-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 align-self-center">
                            <div class="slide-item-info">
                                <div class="slide-item-info-inner ltn__slide-animation">
                                    <div class="slide-video mb-50 d-none">
                                        <a class="ltn__video-icon-2 ltn__video-icon-2-border"
                                            href="https://www.youtube.com/embed/ATI7vfCgwXE?autoplay=1&amp;showinfo=0"
                                            data-rel="lightcase:myCollection">
                                            <i class="fa fa-play"></i>
                                        </a>
                                    </div>
                                    <h6 class="slide-sub-title animated"><img src="{{ asset(path: 'assets/clients/img/icons/icon-img/1.png') }}"
                                            alt="#"> 100% Sản phẩm chính hãng</h6>
                                    <h1 class="slide-title animated ">Thực phẩm được yêu thích nhất <br> từ khu vườn của chúng tôi</h1>
                                    <div class="slide-brief animated">
                                        <p>Sản phẩm tươi sạch, chọn lọc kỹ lưỡng mỗi ngày, mang đến hương vị tự nhiên và an toàn cho gia đình bạn.</p>
                                    </div>
                                    <div class="btn-wrapper animated">
                                        <a href="shop.html"
                                            class="theme-btn-1 btn btn-effect-1 text-uppercase">Khám phá
                                            Sản phẩm</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ltn__slide-item -->
        <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3 ltn__slide-item-3-normal bg-image"
            data-bg="{{ asset(path: 'assets/clients/img/slider/14.jpg') }}">
            <div class="ltn__slide-item-inner  text-right text-end">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 align-self-center">
                            <div class="slide-item-info">
                                <div class="slide-item-info-inner ltn__slide-animation">
                                    <h6 class="slide-sub-title ltn__secondary-color animated">// ĐỘI NGŨ
                                        KỸ SƯ & KỸ THUẬT VIÊN LÀNH NGHỀ</h6>
                                    <h1 class="slide-title animated ">Thơm ngon & Tốt cho sức khỏe <br> Thực phẩm hữu cơ</h1>
                                    <div class="slide-brief animated">
                                        <p>Chọn lọc từ nguồn nguyên liệu sạch, tươi mỗi ngày, đảm bảo an toàn và giữ trọn hương vị tự nhiên.</p>
                                    </div>
                                    <div class="btn-wrapper animated">
                                        <a href="shop.html"
                                            class="theme-btn-1 btn btn-effect-1 text-uppercase">Khám phá
                                            Sản phẩm</a>
                                        <a href="{{ url('/about') }}" class="btn btn-transparent btn-effect-3">TÌM HIỂU
                                            THÊM</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</div>
<!-- SLIDER AREA END -->

<!-- BANNER AREA START -->
<div class="ltn__banner-area mt-120 mb-90">
    <div class="container">
        <div class="row ltn__custom-gutter--- justify-content-center">
            <div class="col-lg-6 col-md-6">
                <div class="ltn__banner-item">
                    <div class="ltn__banner-img">
                        <a href="shop.html"><img src="{{ asset(path: 'assets/clients/img/banner/13.png') }}" alt="Banner Image"></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__banner-item">
                            <div class="ltn__banner-img">
                                <a href="shop.html"><img src="{{ asset(path: 'assets/clients/img/banner/14.png') }}" alt="Banner Image"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="ltn__banner-item">
                            <div class="ltn__banner-img">
                                <a href="shop.html"><img src="{{ asset(path: 'assets/clients/img/banner/15.png') }}" alt="Banner Image"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BANNER AREA END -->

<!-- CATEGORY AREA START -->
<div class="ltn__category-area section-bg-1-- ltn__primary-bg before-bg-1 bg-image bg-overlay-theme-black-5--0 pt-115 pb-90"
    data-bg="{{asset('assets/clients/img/bg/5.jpg')}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2 text-center">
                    <h1 class="section-title white-color">Danh mục</h1>
                </div>
            </div>
        </div>
        <div class="row ltn__category-slider-active slick-arrow-1">
            @foreach ($categories as $category)
            <div class="col-12">
                <div class="ltn__category-item ltn__category-item-3 text-center">
                    <div class="ltn__category-item-img">
                        <a href="shop.html">
                            @php
                            $imagePath = $category->image;
                            $imageUrl = $imagePath
                            ? (str_starts_with($imagePath, 'uploads/')
                            ? asset('storage/' . $imagePath)
                            : asset($imagePath))
                            : asset('storage/uploads/users/categories/rau-cu.png');
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $category->name }}">
                        </a>
                    </div>
                    <div class="ltn__category-item-name">
                        <h5><a href="shop.html">{{ $category->name }}</a></h5>
                        <h6>({{ $category->products_count }} sản phẩm)</h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- CATEGORY AREA END -->

<!-- CALL TO ACTION START (call-to-action-4) -->
<div class="ltn__call-to-action-area ltn__call-to-action-4 bg-image pt-115 pb-120" data-bg="{{asset('assets/clients/img/bg/6.jpg')}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="call-to-action-inner call-to-action-inner-4 text-center">
                    <div class="section-title-area ltn__section-title-2">
                        <h6 class="section-subtitle ltn__secondary-color">// Bất kỳ câu hỏi nào bạn có //</h6>
                        <h1 class="section-title white-color">091-643-1529</h1>
                    </div>
                    <div class="btn-wrapper">
                        <a href="tel:+123456789" class="theme-btn-1 btn btn-effect-1">Gọi Điện</a>
                        <a href="contact.html" class="btn btn-transparent btn-effect-4 white-color">Liên Hệ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ltn__call-to-4-img-1">
        <img src="{{asset('assets/clients/img/bg/12.png')}}" alt="#">
    </div>
    <div class="ltn__call-to-4-img-2">
        <img src="{{asset('assets/clients/img/bg/12.png')}}" alt="#">
    </div>
</div>
<!-- CALL TO ACTION END -->

@endsection