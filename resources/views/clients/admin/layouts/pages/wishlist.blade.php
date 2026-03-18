@extends('clients.admin.layouts.client')

@section('title', 'Yêu thích')
@section('breadcrumb', 'Yêu thích')

@section('content')
    <div class="liton__shoping-cart-area mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping-cart-inner">
                        <div class="shoping-cart-table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Sản phẩm</th>
                                        <th>Tên</th>
                                        <th>Giá</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($wishlists as $wishlist)
                                        @php($product = $wishlist->product)
                                        <tr>
                                            <td class="cart-product-remove">
                                                @if($product)
                                                    <form action="{{ route('wishlist.remove') }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <button type="submit">x</button>
                                                    </form>
                                                @else
                                                    <button type="button" disabled>x</button>
                                                @endif
                                            </td>
                                            <td class="cart-product-image">
                                                <a href="{{ $product ? route('products.detail', $product->slug) : 'javascript:void(0)' }}">
                                                    <img src="{{ $product?->image_url ?? asset('storage/uploads/products/default-product.png') }}"
                                                        alt="{{ $product?->name ?? 'Sản phẩm' }}">
                                                </a>
                                            </td>
                                            <td class="cart-product-info">
                                                <h4>
                                                    <a href="{{ $product ? route('products.detail', $product->slug) : 'javascript:void(0)' }}">
                                                        {{ $product?->name ?? 'Sản phẩm không tồn tại' }}
                                                    </a>
                                                </h4>
                                            </td>
                                            <td class="cart-product-price">{{ number_format($product?->price ?? 0, 0, ',', '.') }}Đ</td>
                                            <td class="cart-product-add">
                                                @if($product)
                                                    <form action="{{ route('wishlist.add_to_cart') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="theme-btn-1 btn btn-effect-1">Thêm vào giỏ hàng</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Danh sách yêu thích của bạn đang trống</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
    