@extends('clients.admin.layouts.client')

@section('title', 'Dịch vụ')
@section('breadcrumb', 'Dịch vụ')

@section('content')
        <!-- ABOUT US AREA START -->
        <div class="ltn__about-us-area pb-115">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 align-self-center">
                        <div class="about-us-img-wrap ltn__img-shape-left  about-img-left">
                            <img src="{{ asset('img/service/11.jpg') }}" alt="Image">
                        </div>
                    </div>
                    <div class="col-lg-7 align-self-center">
                        <div class="about-us-info-wrap">
                            <div class="section-title-area ltn__section-title-2">
                                <h6 class="section-subtitle ltn__secondary-color">//  DỊCH VỤ UY TÍN</h6>
                                <h1 class="section-title">Chúng tôi - Đủ Tài, Trọn Tâm<span>.</span></h1>
                                <p>Không chỉ là nhà cung cấp, chúng tôi là người đồng hành đáng tin cậy, mang đến giải pháp trọn vẹn cho gia đình và doanh nghiệp của bạn.</p>
                            </div>
                            <div class="about-us-info-wrap-inner about-us-info-devide">
                                <p>Với hơn 10 năm kinh nghiệm trong lĩnh vực phân phối thực phẩm sạch và sản phẩm tiêu dùng, 
                                    chúng tôi tự hào xây dựng một hệ thống vận hành chuyên nghiệp, khép kín từ khâu nhập hàng đến tay người tiêu dùng. 
                                    Sự hài lòng của bạn chính là thước đo giá trị cốt lõi của chúng tôi. 
                                    Mỗi sản phẩm gửi đến khách hàng không chỉ đảm bảo chất lượng tuyệt đối mà còn chứa đựng sự trân trọng và 
                                    mong muốn mang lại cuộc sống tiện nghi, khỏe mạnh hơn cho cộng đồng.</p>
                                <div class="list-item-with-icon">
                                    <ul>
                                        <li><a href="contact.html">🚚 Giao hàng tận nơi miễn phí trong 24h</a></li>
                                        <li><a href="{{ url('/team') }}">👥 Đội ngũ chuyên gia giàu kinh nghiệm</a></li>
                                        <li><a href="service-details.html">✅ Trang thiết bị hiện đại, an toàn tuyệt đối</a></li>
                                        <li><a href="shop.html">🛍️ Đa dạng sản phẩm chính hãng, không giới hạn</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ABOUT US AREA END -->

        <!-- SERVICE AREA START (Service 1) -->
       <div class="ltn__service-area section-bg-1 pt-115 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2 text-center">
                    <h1 class="section-title white-color---">Dịch vụ của chúng tôi</h1>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-4 col-sm-6">
                <div class="ltn__service-item-1">
                    <div class="service-item-img">
                        <a href="service-details.html"><img src="{{ asset('img/service/1.jpg') }}" alt="Dịch vụ 1"></a>
                    </div>
                    <div class="service-item-brief">
                        <h3><a href="service-details.html">Rau củ hữu cơ tươi mỗi ngày</a></h3>
                        <p>Tuyển chọn từ nông trại đạt chuẩn, rau củ luôn xanh tươi, sạch lành và giao nhanh trong ngày.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="ltn__service-item-1">
                    <div class="service-item-img">
                        <a href="service-details.html"><img src="{{ asset('img/service/2.jpg') }}" alt="Dịch vụ 2"></a>
                    </div>
                    <div class="service-item-brief">
                        <h3><a href="service-details.html">Trái cây theo mùa – ngọt tự nhiên</a></h3>
                        <p>Ưu tiên trái cây đúng vụ, hương vị đậm đà, hạn chế thuốc bảo quản, an tâm cho cả gia đình.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="ltn__service-item-1">
                    <div class="service-item-img">
                        <a href="service-details.html"><img src="{{ asset('img/service/3.jpg') }}" alt="Dịch vụ 3"></a>
                    </div>
                    <div class="service-item-brief">
                        <h3><a href="service-details.html">Combo bữa ăn tiện lợi</a></h3>
                        <p>Gợi ý thực đơn cân bằng dinh dưỡng, nguyên liệu sơ chế gọn gàng, giúp bạn nấu ngon trong tích tắc.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="ltn__service-item-1">
                    <div class="service-item-img">
                        <a href="service-details.html"><img src="{{ asset('img/service/3.jpg') }}" alt="Dịch vụ 4"></a>
                    </div>
                    <div class="service-item-brief">
                        <h3><a href="service-details.html">Đặc sản sạch – chuẩn nguồn gốc</a></h3>
                        <p>Đặc sản vùng miền được kiểm tra chất lượng, minh bạch nguồn gốc, để bạn thưởng thức trọn vị.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="ltn__service-item-1">
                    <div class="service-item-img">
                        <a href="service-details.html"><img src="{{ asset('img/service/1.jpg') }}" alt="Dịch vụ 5"></a>
                    </div>
                    <div class="service-item-brief">
                        <h3><a href="service-details.html">Giao hàng nhanh, đóng gói kỹ</a></h3>
                        <p>Đóng gói chống dập nát, giữ độ tươi ngon; giao đúng hẹn để bữa ăn của bạn luôn sẵn sàng.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="ltn__service-item-1">
                    <div class="service-item-img">
                        <a href="service-details.html"><img src="{{ asset('img/service/2.jpg') }}" alt="Dịch vụ 6"></a>
                    </div>
                    <div class="service-item-brief">
                        <h3><a href="service-details.html">Tư vấn chọn thực phẩm theo nhu cầu</a></h3>
                        <p>Hỗ trợ chọn sản phẩm phù hợp chế độ ăn (eat clean, giảm cân, mẹ bầu…), tối ưu sức khỏe mỗi ngày.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- SERVICE AREA END -->

   <!-- OUR JOURNEY AREA START -->
<div class="ltn__our-journey-area bg-image bg-overlay-theme-90 pt-280 pb-350 mb-35 plr--9"
    data-bg="{{ asset('img/bg/8.jpg') }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__our-journey-wrap ">
                    <ul>
                        <li>
                            <span class="ltn__journey-icon">1990</span>
                            <ul>
                                <li>
                                    <div class="ltn__journey-history-item-info clearfix">
                                        <div class="ltn__journey-history-img">
                                            <img src="{{ asset('img/service/history-1.jpg') }}" alt="Cột mốc 1990">
                                        </div>
                                        <div class="ltn__journey-history-info">
                                            <h3>Khởi đầu hành trình</h3>
                                            <p>Từ những ngày đầu đầy nhiệt huyết, chúng tôi đặt nền móng cho một thương hiệu tử tế: làm sạch từ nguồn và chọn lựa bằng sự tận tâm.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li class="active">
                            <span class="ltn__journey-icon">2000</span>
                            <ul>
                                <li>
                                    <div class="ltn__journey-history-item-info clearfix">
                                        <div class="ltn__journey-history-img">
                                            <img src="{{ asset('img/service/history-1.jpg') }}" alt="Cột mốc 2000">
                                        </div>
                                        <div class="ltn__journey-history-info">
                                            <h3>Ghi dấu niềm tin</h3>
                                            <p>Nhờ chất lượng ổn định và dịch vụ chỉn chu, chúng tôi dần trở thành lựa chọn quen thuộc của nhiều gia đình yêu thực phẩm sạch.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <span class="ltn__journey-icon">2010</span>
                            <ul>
                                <li>
                                    <div class="ltn__journey-history-item-info clearfix">
                                        <div class="ltn__journey-history-img">
                                            <img src="{{ asset('img/service/history-1.jpg') }}" alt="Cột mốc 2010">
                                        </div>
                                        <div class="ltn__journey-history-info">
                                            <h3>Bứt phá và phát triển</h3>
                                            <p>Mở rộng vùng nguyên liệu, nâng chuẩn kiểm định, tối ưu quy trình bảo quản để mỗi sản phẩm đến tay bạn luôn tươi ngon và an toàn.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <span class="ltn__journey-icon">2020</span>
                            <ul>
                                <li>
                                    <div class="ltn__journey-history-item-info clearfix">
                                        <div class="ltn__journey-history-img">
                                            <img src="{{ asset('img/service/history-1.jpg') }}" alt="Cột mốc 2020">
                                        </div>
                                        <div class="ltn__journey-history-info">
                                            <h3>Nâng tầm trải nghiệm</h3>
                                            <p>Chúng tôi cải tiến dịch vụ giao hàng, đóng gói kỹ lưỡng và tư vấn tận tình, giúp việc mua sắm thực phẩm trở nên dễ dàng hơn bao giờ hết.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <span class="ltn__journey-icon">2026</span>
                            <ul>
                                <li>
                                    <div class="ltn__journey-history-item-info clearfix">
                                        <div class="ltn__journey-history-img">
                                            <img src="{{ asset('img/service/history-1.jpg') }}" alt="Cột mốc 2026">
                                        </div>
                                        <div class="ltn__journey-history-info">
                                            <h3>Lan tỏa lối sống xanh</h3>
                                            <p>Không chỉ bán sản phẩm, chúng tôi đồng hành cùng cộng đồng: khuyến khích ăn lành, sống xanh và lựa chọn bền vững cho tương lai.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- OUR JOURNEY AREA END -->
@endsection
    