              <div class="ltn__utilize-menu-head">
                  <span class="ltn__utilize-menu-title">Giỏ hàng</span>
                  <button class="ltn__utilize-close">×</button>
              </div>
              <div class="mini-cart-product-area ltn__scrollbar">

                    @php
                        $cartLines = $cartLines ?? [];
                        $subtotal = (float) ($subtotal ?? 0);
                    @endphp

                    @if (!empty($cartLines) && count($cartLines) > 0)
                        @foreach ($cartLines as $line)
                            @php
                                $product = $line['product'] ?? null;
                                $quantity = (int) ($line['quantity'] ?? 0);
                            @endphp

                            @if ($product && $quantity > 0)
                                <div class="mini-cart-item clearfix">
                                    <div class="mini-cart-img">
                                        <a href="javasript:void(0)">
                                            <img src="{{ $product->image_url }}" alt="Image">
                                        </a>
                                        <span class="mini-cart-item-delete" data-id="{{ $product->id }}">
                                            <i class="icon-cancel"></i>
                                        </span>
                                    </div>
                                    <div class="mini-cart-info">
                                        <h6><a href="#">{{ $product->name }}</a></h6>
                                        <span class="mini-cart-quantity">{{ $quantity }} x {{ number_format($product->price, 0, ',', '.') }}VNĐ</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="mini-cart-item clearfix">
                            <div class="mini-cart-info">
                                <h6>Giỏ hàng trống</h6>
                            </div>
                        </div>
                    @endif
              </div>
              <div class="mini-cart-footer">
                  <div class="mini-cart-sub-total">
                      <h5>Tổng tiền: <span>{{number_format($subtotal, 0, ',', '.')}}VNĐ</span></h5>
                  </div>
                  <div class="btn-wrapper">
                      <a href="cart.html" class="theme-btn-1 btn btn-effect-1">Xem giỏ hàng</a>
                      <a href="cart.html" class="theme-btn-2 btn btn-effect-2">Thanh toán</a>
                  </div>
              </div>