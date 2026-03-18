@extends('clients.admin.layouts.client')

@section('title', 'Team')
@section('breadcrumb', 'Team')

@section('content')

        <!-- PROGRESS BAR AREA START -->
        <div class="ltn__progress-bar-area pt-115 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="ltn__progress-bar-wrap">
                    <div class="section-title-area ltn__section-title-2">
                        <h6 class="section-subtitle ltn__secondary-color">// kỹ năng</h6>
                        <h1 class="section-title">Chúng tôi sở hữu đội ngũ
                            chuyên nghiệp hàng đầu<span>.</span></h1>
                        <p>Với kinh nghiệm thực tế và tinh thần làm việc tận tâm, chúng tôi không ngừng nâng cao chất lượng để mang đến dịch vụ tốt nhất cho khách hàng.</p>
                    </div>

                    <div class="ltn__progress-bar-inner">
                        <div class="ltn__progress-bar-item">
                            <p>Chăm sóc vườn</p>
                            <div class="progress">
                                <div class="progress-bar wow fadeInLeft" data-wow-duration="0.5s"
                                    data-wow-delay=".5s" role="progressbar" style="width: 72%">
                                    <span>72%</span>
                                </div>
                            </div>
                        </div>

                        <div class="ltn__progress-bar-item">
                            <p>Thiết kế cảnh quan</p>
                            <div class="progress">
                                <div class="progress-bar wow fadeInLeft" data-wow-duration="0.5s"
                                    data-wow-delay=".5s" role="progressbar" style="width: 74%">
                                    <span>74%</span>
                                </div>
                            </div>
                        </div>

                        <div class="ltn__progress-bar-item">
                            <p>Trồng rau sạch</p>
                            <div class="progress">
                                <div class="progress-bar wow fadeInLeft" data-wow-duration="0.5s"
                                    data-wow-delay=".5s" role="progressbar" style="width: 81%">
                                    <span>81%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 align-self-center">
                <div class="about-img-right">
                    <img src="{{ asset('assets/clients/img/team/t-4.jpg') }}" alt="Hình ảnh đội ngũ">
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- PROGRESS BAR AREA END -->
@endsection
    