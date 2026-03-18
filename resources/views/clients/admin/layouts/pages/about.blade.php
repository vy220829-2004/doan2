@extends('clients.admin.layouts.client')

@section('title', 'Về chúng tôi')
@section('breadcrumb', 'Về chúng tôi')

@section('content')
        <!-- ABOUT US AREA START -->
        <div class="ltn__about-us-area pt-120--- pb-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-img-wrap about-img-left">
                            <img src="img/others/6.png" alt="About Us Image">
                        </div>
                    </div>
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-info-wrap">
                            <div class="section-title-area ltn__section-title-2">
                                <h6 class="section-subtitle ltn__secondary-color">Tìm Hiểu Về Cửa Hàng </h6>
                                <h1 class="section-title">Trusted Organic <br class="d-none d-md-block"> Hửu Cơ Uy Tín</h1>
                                <p>Chúng tôi cam kết mang đến cho khách hàng những sản phẩm hữu cơ chất lượng cao, an toàn và lành mạnh cho sức khỏe của bạn.</p>
                            </div>
                            <p>Những người bán hàng luôn hướng tới điều tốt đẹp, lang tỏa giá trị tức cực.
                                Chúng tôi là một thị trường dân chủ, tự duy trì, hoạt động dựa trên niềm tin cộng đồng và chất lượng.
                            </p>
                            <div class="about-author-info d-flex">
                                <div class="author-name-designation  align-self-center">
                                    <h4 class="mb-0">DevVy</h4>
                                    <small>/ Giám Đốc Cửa Hàng </small>
                                </div>
                                <div class="author-sign">
                                    <img src="{{ asset('assets/clients/img/icons/icon-img/author-sign.png') }}" alt="#">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ABOUT US AREA END -->

        <!-- FEATURE AREA START ( Feature - 6) -->
        <div class="ltn__feature-area section-bg-1 pt-115 pb-90">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area ltn__section-title-2 text-center">
                            <h6 class="section-subtitle ltn__secondary-color">// Đặc điểm //</h6>
                            <h1 class="section-title">Tại Sao Nên Chọn Chúng Tôi<span>.</span></h1>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="ltn__feature-item ltn__feature-item-7">
                            <div class="ltn__feature-icon-title">
                                <div class="ltn__feature-icon">
                                    <span><img src="{{ asset('assets/clients/img/icons/icon-img/21.png') }}" alt="#"></span>
                                </div>
                                <h3><a href="service-details.html">Đa Dạng Sản Phẩm</a></h3>
                            </div>
                            <div class="ltn__feature-info">
                                <p>Chúng tôi cung cấp nhiều thương hiệu uy tín và đảm bảo chất lượng nguồn gốc rõ ràng.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="ltn__feature-item ltn__feature-item-7">
                            <div class="ltn__feature-icon-title">
                                <div class="ltn__feature-icon">
                                    <span><img src="{{ asset('assets/clients/img/icons/icon-img/22.png') }}" alt="#"></span>
                                </div>
                                <h3><a href="service-details.html">Sản Phẩm Tuyển Chọn</a></h3>
                            </div>
                            <div class="ltn__feature-info">
                                <p>Mỗi sản phẩm được tuyển chọn kỹ lưỡng,mang đến sự an tâm và đảm bảo chất lượng và độ tươi mới đến khách hàng.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="ltn__feature-item ltn__feature-item-7">
                            <div class="ltn__feature-icon-title">
                                <div class="ltn__feature-icon">
                                    <span><img src="{{ asset('assets/clients/img/icons/icon-img/23.png') }}" alt="#"></span>
                                </div>
                                <h3><a href="service-details.html">Không Chứa Thuốc Trừ Sâu</a></h3>
                            </div>
                            <div class="ltn__feature-info">
                                <p>Chúng tôi cam kết cung cấp các sản phẩm không chứa thuốc trừ sâu, đảm bảo an toàn cho sức khỏe người tiêu dùng.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FEATURE AREA END -->

@endsection
    