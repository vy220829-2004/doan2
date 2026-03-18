@extends('clients.admin.layouts.client')

@section('title', 'Tìm kiếm')
@section('breadcrumb', 'Tìm kiếm')

@section('content')
<div class="container">
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @foreach($products as $product)
        <div class="col-md-3">
            <div class="product-item">
                <a href="{{ route('products.detail', ['slug' => $product->slug]) }}">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid">
                </a>

                <h5 class="product-title mt-2">
                    <a href="{{ route('products.detail', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
                </h5>
                <div class="product-price">
                    {{ number_format($product->price, 0, ',', '.') }} VNĐ
                </div>

            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection