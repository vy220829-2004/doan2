<?php

namespace App\Http\Controllers\Clients;

use App\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ReviewController extends Controller
{
    public function index(Product $product): string
    {
    // Render HTML review list for AJAX refresh
    return view('clients.admin.layouts.components.includes.review-list', compact('product'))->render();
    }

     public function createReview(Request $request)
     {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required_without:content|string',
            'content' => 'required_without:comment|string',
        ]);

        $review = new Review();
        $review->user_id = Auth::id();
        $review->product_id = $request->product_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment ?? $request->content;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được gửi thành công.',
            
        ],200);
     }
}
