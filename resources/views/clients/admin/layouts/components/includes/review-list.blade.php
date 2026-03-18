@foreach ($product->reviews as $review)
<div class="it_comment_item clearfix">
    <div class="it_comment_avatar">
        <img src="{{ $review->user->avatar_url }}" alt="Image">
    </div>
    <div class="it_comment_content">
        <h6 class="it_comment_name">{{ $review->user->name }}</h6>
        <div class="product-ratting">
            <ul>
                @for ($i = 1; $i <= 5; $i++)
                    <li>
                        @if ($i <= $review->rating)
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    </li>
                @endfor
            </ul>
        </div>
        <p>{{ $review->comment }}</p>
        <span class="it_comment_reply_date">{{ $review->created_at->format('M d, Y') }}</span>
    </div>
</div>
@endforeach