@extends('clients.admin.layouts.client')

@section('title', 'Chi tiết sản phẩm')
@section('breadcrumb', 'Chi tiết sản phẩm')

@section('content')
<!-- SHOP DETAILS AREA START -->
<div class="ltn__shop-details-area pb-85">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ltn__shop-details-inner mb-60">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="ltn__shop-details-img-gallery">
                                <div class="ltn__shop-details-large-img">
                                    <div class="single-large-img">
                                        @foreach ($product->images as $image)
                                        <a href="{{ $image->image_url }}" data-rel="lightcase:myCollection">
                                            <img src="{{ $image->image_url }}" alt="{{$product->name}}">
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="ltn__shop-details-small-img slick-arrow-2">
                                    @foreach ($product->images as $image)
                                    <div class="single-small-img">
                                        <img src="{{ $image->image_url }}" alt="{{$product->name}}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="modal-product-info shop-details-info pl-0">
                                <h3>{{$product->name}}</h3>
                                <div class="product-price">
                                    <span>{{ number_format($product->price, 0, ',', '.') }}VNĐ</span>
                                </div>
                                <div class="modal-product-meta ltn__product-details-menu-1">
                                    <ul>
                                        <li>
                                            <strong>Danh mục:</strong>
                                            <span>
                                                <a href="javascript:void(0)">{{ $product->category?->name ?? '' }}</a>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="ltn__product-details-menu-2">
                                    <ul>
                                        <li>
                                            <div class="cart-plus-minus">
                                                <div class="dec qtybutton">-</div>
                                                <input
                                                    type="number"
                                                    value="1"
                                                    name="quantity"
                                                    class="cart-plus-minus-box"
                                                    inputmode="numeric"
                                                    min="1"
                                                    @if(!is_null($product->stock)) max="{{ (int) $product->stock }}" @endif
                                                    data-max="{{ $product->stock }}">
                                                 <div class="inc qtybutton">+</div>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="theme-btn-1 btn btn-effect-1 js-add-to-cart" title="Thêm vào giỏ hàng"
                                                data-url="{{ route('cart.add') }}" data-product-id="{{ $product->id }}">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span>Thêm vào giỏ hàng</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="ltn__product-details-menu-3">
                                    <ul>
                                        <li>
                                            <a href="#" class="" title="Wishlist" data-bs-toggle="modal"
                                                data-bs-target="#liton_wishlist_modal-{{ $product->id }}">
                                                <i class="far fa-heart"></i>
                                                <span>Yêu thích</span>
                                            </a>
                                        </li>
                                        <li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="ltn__social-media">
                                    <ul>
                                        <li>chia sẻ:</li>
                                        <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a>
                                        </li>
                                        <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                                        </li>

                                    </ul>
                                </div>
                                <hr>
                                <div class="ltn__safe-checkout">
                                    <h5>Đảm bảo thanh toán an toàn</h5>
                                    <img src="{{ asset('assets/clients/img/icons/payment-2.png') }}" alt="Payment Image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Shop Tab Start -->
                <div class="ltn__shop-details-tab-inner ltn__shop-details-tab-inner-2">
                    <div class="ltn__shop-details-tab-menu">
                        <div class="nav">
                            <a class="active show" data-bs-toggle="tab"
                                href="#liton_tab_details_description">Mô tả</a>
                            <a data-bs-toggle="tab" href="#liton_tab_details_reviews" class="">Đánh giá</a>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="liton_tab_details_description">
                            <div class="ltn__shop-details-tab-content-inner">
                                <h4 class="title-2">Mô tả</h4>
                                <p>{{$product->description}}</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="liton_tab_details_reviews">
                            <div class="ltn__shop-details-tab-content-inner">
                                <h4 class="title-2">Đánh giá khách hàng</h4>
                                <hr>
                                <!-- comment-area -->
                                <div class="ltn__comment-area mb-30">
                                    <div class="ltn__comment-inner">
                                        <div class="it_comment_list">
                                            @include('clients.admin.layouts.components.includes.review-list', ['product' => $product])
                                        </div>
                                    </div>
                                </div>
                                <!-- comment-reply -->
                                <div class="ltn__comment-reply-area ltn__form-box mb-30">
                                    <form id="review-form" data-product-id={{ $product->id }} data-url="{{ route('review.store') }}">
                                        <h4 class="title-2">Thêm đánh giá</h4>
                                        <div id="review-message"></div>
                                        <div class="mb-30">
                                            <div class="add-a-review">
                                                <h6>Số sao:</h6>
                                                <div class="product-ratting">
                                                    <ul>
                                                        @for ($i = 1; $i <= 5; $i++)
                                                         <li><a href="javascript:void(0)" class="star-rating" data-rating="{{ $i }}">
                                                        <i class="far fa-star"></i></a></li>
                                                        @endfor
                                                       
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="rating" id="rating-value" value="0">
                                        <div class="input-item input-item-textarea ltn__custom-icon">
                                            <textarea placeholder="Nhập đánh giá của bạn...." id = "review-content"></textarea>
                                        </div>
                                        <div class="btn-wrapper">
                                            <button class="btn theme-btn-1 btn-effect-1 text-uppercase"
                                                type="submit">Gửi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Shop Tab End -->
            </div>
        </div>
    </div>
</div>
<!-- SHOP DETAILS AREA END -->

@include('clients.admin.layouts.components.includes.includes-modals')

<!-- PRODUCT SLIDER AREA START -->
<div class="ltn__product-slider-area ltn__product-gutter pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2">
                    <h1 class="section-title">Sản phẩm tương tự<span>.</span></h1>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse(($relatedProducts ?? collect()) as $relatedProduct)
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="ltn__product-item ltn__product-item-3 text-center">
                    <div class="product-img">
                        <a href="{{ route('products.detail', $relatedProduct->slug) }}">
                            <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}">
                        </a>
                        <div class="product-hover-action">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);" title="Xem nhanh"
                                        data-bs-toggle="modal"
                                        data-bs-target="#quick_view_modal-{{ $relatedProduct->id }}">
                                        <i class="far fa-eye"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" title="Thêm vào giỏ hàng"
                                        class="js-add-to-cart"
                                        data-url="{{ route('cart.add') }}"
                                        data-product-id="{{ $relatedProduct->id }}">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" title="Yêu thích"
                                        data-bs-toggle="modal"
                                        data-bs-target="#liton_wishlist_modal-{{ $relatedProduct->id }}">
                                        <i class="far fa-heart"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-info">
                        <h2 class="product-title">
                            <a href="{{ route('products.detail', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a>
                        </h2>
                        <div class="product-price">
                            <span>{{ number_format($relatedProduct->price, 0, ',', '.') }}VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>
        @foreach ($relatedProducts as $product)
        @include('clients.admin.layouts.components.includes.includes-modals')
        @endforeach
    </div>
</div>
<!-- PRODUCT SLIDER AREA END -->

@endsection