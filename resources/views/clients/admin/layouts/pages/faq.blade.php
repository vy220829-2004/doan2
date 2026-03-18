@extends('clients.admin.layouts.client')

@section('title', 'FAQ')
@section('breadcrumb', 'Những câu hỏi thường gặp')

@section('content')
<!-- FAQ AREA START (faq-2) (ID > accordion_2) -->
<div class="ltn__faq-area mb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="ltn__faq-inner ltn__faq-inner-2">
                    <div id="accordion_2">
                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-1" aria-expanded="false">
                                Cách đặt mua sản phẩm như thế nào?
                            </h6>
                            <div id="faq-item-2-1" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Bạn chỉ cần chọn sản phẩm yêu thích, thêm vào giỏ hàng và điền thông tin giao
                                        nhận. Sau khi đặt hàng thành công, hệ thống sẽ gửi xác nhận và chúng tôi sẽ liên
                                        hệ để chốt đơn, thời gian giao hàng dự kiến cũng được cập nhật rõ ràng.</p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="ltn__card-title" data-bs-toggle="collapse" data-bs-target="#faq-item-2-2"
                                aria-expanded="true">
                                Làm sao để yêu cầu hoàn tiền trên website?
                            </h6>
                            <div id="faq-item-2-2" class="collapse show" data-parent="#accordion_2">
                                <div class="card-body">
                                    <div class="ltn__video-img alignleft">
                                        <img src="{{ asset('assets/clients/img/bg/17.jpg') }}" alt="Hướng dẫn hoàn tiền">
                                        <a class="ltn__video-icon-2 ltn__video-icon-2-small ltn__video-icon-2-border----"
                                            href="https://www.youtube.com/embed/LjCzPp-MK48?autoplay=1&amp;showinfo=0"
                                            data-rel="lightcase:myCollection">
                                            <i class="fa fa-play"></i>
                                        </a>
                                    </div>
                                    <p>Nếu đơn hàng gặp vấn đề (thiếu hàng, hư hỏng, sai sản phẩm…), bạn vui lòng liên hệ
                                        hỗ trợ và cung cấp mã đơn hàng kèm hình ảnh. Chúng tôi sẽ kiểm tra trong thời
                                        gian sớm nhất và tiến hành đổi/trả hoặc hoàn tiền theo chính sách.</p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-3" aria-expanded="false">
                                Tôi là khách hàng mới, nên bắt đầu từ đâu?
                            </h6>
                            <div id="faq-item-2-3" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Bạn có thể bắt đầu bằng cách tạo tài khoản để lưu địa chỉ giao hàng và theo dõi đơn
                                        dễ dàng. Nếu chưa biết chọn gì, hãy thử các combo “bán chạy” hoặc “theo mùa” để
                                        trải nghiệm nhanh chất lượng sản phẩm.</p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-4" aria-expanded="false">
                                Chính sách đổi trả &amp; hoàn tiền
                            </h6>
                            <div id="faq-item-2-4" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Chúng tôi ưu tiên quyền lợi khách hàng: hỗ trợ đổi/trả khi sản phẩm bị lỗi, hư hỏng
                                        trong quá trình vận chuyển hoặc giao sai. Bạn vui lòng phản hồi sớm sau khi nhận
                                        hàng để đội ngũ xử lý nhanh và thỏa đáng.</p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-5" aria-expanded="false">
                                Thông tin cá nhân của tôi có được bảo mật không?
                            </h6>
                            <div id="faq-item-2-5" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Có. Chúng tôi cam kết bảo mật thông tin khách hàng và chỉ sử dụng cho mục đích xử
                                        lý đơn hàng, chăm sóc và hỗ trợ. Dữ liệu được lưu trữ an toàn, hạn chế truy cập
                                        trái phép.</p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-6" aria-expanded="false">
                                Mã giảm giá không áp dụng được, phải làm sao?
                            </h6>
                            <div id="faq-item-2-6" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Bạn hãy kiểm tra điều kiện áp dụng (thời hạn, đơn tối thiểu, danh mục sản phẩm).
                                        Nếu vẫn không dùng được, vui lòng gửi ảnh chụp màn hình và mã giảm giá để chúng
                                        tôi kiểm tra và hỗ trợ ngay.</p>
                                </div>
                            </div>
                        </div>

                        <!-- card -->
                        <div class="card">
                            <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                data-bs-target="#faq-item-2-7" aria-expanded="false">
                                Tôi có thể thanh toán bằng thẻ tín dụng như thế nào?
                            </h6>
                            <div id="faq-item-2-7" class="collapse" data-parent="#accordion_2">
                                <div class="card-body">
                                    <p>Tại bước thanh toán, bạn chọn phương thức “Thẻ tín dụng/ghi nợ”, nhập thông tin thẻ
                                        theo hướng dẫn và xác thực (nếu có). Giao dịch được xử lý qua cổng thanh toán an
                                        toàn để đảm bảo bảo mật cho bạn.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="need-support text-center mt-100">
                        <h2>Bạn vẫn cần hỗ trợ? Liên hệ đội ngũ CSKH 24/7:</h2>
                        <div class="btn-wrapper mb-30">
                            <a href="contact.html" class="theme-btn-1 btn">Liên hệ ngay</a>
                        </div>
                        <h3><i class="fas fa-phone"></i> +0916-4312-59</h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <aside class="sidebar-area ltn__right-sidebar">
                    <!-- Newsletter Widget -->
                    <div class="widget ltn__search-widget ltn__newsletter-widget">
                        <h6 class="ltn__widget-sub-title">// đăng ký</h6>
                        <h4 class="ltn__widget-title">Nhận bản tin ưu đãi</h4>
                        <form action="#">
                            <input type="text" name="search" placeholder="Nhập từ khóa...">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                        <div class="ltn__newsletter-bg-icon">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                    </div>

                    <!-- Banner Widget -->
                    <div class="widget ltn__banner-widget">
                        <a href="shop.html"><img src="{{ asset('assets/clients/img/banner/banner-3.jpg') }}" alt="Banner khuyến mãi"></a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>
<!-- FAQ AREA END -->

        <!-- COUNTER UP AREA START -->
        <div class="ltn__counterup-area bg-image bg-overlay-theme-black-80 pt-115 pb-70" data-bg="{{ asset('assets/clients/img/bg/5.jpg') }}">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 align-self-center">
                        <div class="ltn__counterup-item-3 text-color-white text-center">
                            <div class="counter-icon"> <img src="{{ asset('assets/clients/img/icons/icon-img/2.png') }}" alt="#"> </div>
                            <h1><span class="counter">733</span><span class="counterUp-icon">+</span> </h1>
                            <h6>Active Clients</h6>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 align-self-center">
                        <div class="ltn__counterup-item-3 text-color-white text-center">
                            <div class="counter-icon"> <img src="{{ asset('assets/clients/img/icons/icon-img/3.png') }}" alt="#"> </div>
                            <h1><span class="counter">33</span><span class="counterUp-letter">K</span><span
                                    class="counterUp-icon">+</span> </h1>
                            <h6>Cup Of Coffee</h6>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 align-self-center">
                        <div class="ltn__counterup-item-3 text-color-white text-center">
                            <div class="counter-icon"> <img src="{{ asset('assets/clients/img/icons/icon-img/4.png') }}" alt="#"> </div>
                            <h1><span class="counter">100</span><span class="counterUp-icon">+</span> </h1>
                            <h6>Get Rewards</h6>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 align-self-center">
                        <div class="ltn__counterup-item-3 text-color-white text-center">
                            <div class="counter-icon"> <img src="{{ asset('assets/clients/img/icons/icon-img/5.png') }}" alt="#"> </div>
                            <h1><span class="counter">21</span><span class="counterUp-icon">+</span> </h1>
                            <h6>Country Cover</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- COUNTER UP AREA END -->
@endsection
    