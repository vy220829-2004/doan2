<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
  private const PRODUCTS_PER_PAGE = 6;

  /**
   * Mapping danh mục -> danh sách sản phẩm hiển thị theo yêu cầu.
   * Lưu ý: "Thực phẩm khác" sẽ hiển thị tất cả sản phẩm (không áp filter).
   */
  private const CATEGORY_PRODUCT_MAP = [
    'ca' => [
      'Cá hồi phi lê',
    ],
    'rau-cu' => [
      'Khoai tây',
      'Cà chua',
      'Rau muống',
      'Xà lách',
      'Dưa leo',
      'Cà rốt',
      'Bắp cải',
      'Hành lá',
      'Gừng',
      'Khoai lang',
      'Củ xung',
      'Mướp đắng',
    ],
    'trai-cay' => [
      'Chuối',
      'Táo xanh',
      'Nho',
      'Cam sành',
      'Bưởi da xanh',
      'Dưa hấu',
    ],
    'thit' => [
      'Thịt heo ba rọi',
      'Thịt bò',
      'Cá hồi phi lê',
      'Tôm sú',
      'Gà ta',
    ],
  ];

  public function index(Request $request)
    {
    $categories = Category::query()
      ->orderByRaw("CASE WHEN slug = 'thuc-pham-khac' THEN 1 ELSE 0 END")
      ->orderBy('name')
      ->get();

    $productsQuery = Product::query()
        ->with('firstImage')
        ->where('status', 'in_stock');

    if ($request->filled('category_id')) {
      $this->applyCategoryFilter($productsQuery, (int) $request->input('category_id'));
    }

    $products = $productsQuery->paginate(self::PRODUCTS_PER_PAGE)->withQueryString();

    return view('clients.admin.layouts.pages.products', compact('categories', 'products'));
    }

  public function filter(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'category_id' => ['nullable', 'integer', 'exists:categories,id'],
      'min_price' => ['nullable', 'numeric', 'min:0'],
      'max_price' => ['nullable', 'numeric', 'min:0'],
      'sort_by' => ['nullable', 'in:default,latest,price_asc,price_desc'],
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Dữ liệu lọc không hợp lệ.',
        'errors' => $validator->errors(),
      ], 422);
    }

    $productsQuery = Product::query()
      ->with('firstImage')
      ->where('status', 'in_stock');

    if ($request->filled('category_id')) {
      $this->applyCategoryFilter($productsQuery, (int) $request->input('category_id'));
    }

    if ($request->filled('min_price')) {
      $productsQuery->where('price', '>=', (float) $request->input('min_price'));
    }

    if ($request->filled('max_price')) {
      $productsQuery->where('price', '<=', (float) $request->input('max_price'));
    }

    $sortBy = $request->input('sort_by', 'default');
    switch ($sortBy) {
      case 'latest':
        $productsQuery->orderByDesc('created_at');
        break;
      case 'price_asc':
        $productsQuery->orderBy('price', 'asc');
        break;
      case 'price_desc':
        $productsQuery->orderBy('price', 'desc');
        break;
      case 'default':
      default:
        $productsQuery->orderByDesc('id', 'desc');
        break;
    }

    $products = $productsQuery->paginate(self::PRODUCTS_PER_PAGE)->withQueryString();

    return response()->json([
      'success' => true,
      'html' => view('clients.admin.layouts.components.pagination.products_grid', compact('products'))->render(),
      'pagination' => $products->links('clients.components.pagination.pagination_custom')->toHtml(),
    ]);
  }

  public function detail($slug)
  {
    $product = Product::query()
      ->with(['category', 'images', 'firstImage','reviews.user'])
      ->where('slug', $slug)
      ->firstOrFail();
    $relatedProducts = Product::query()
      ->with('firstImage')
      ->where('category_id', $product->category_id)
      ->where('id', '!=', $product->id)
      ->limit(4)
      ->get();

    return view('clients.admin.layouts.pages.product-detail', compact('product', 'relatedProducts'));
  }

  private function applyCategoryFilter(Builder $query, int $categoryId): void
  {
    $category = Category::query()->select(['id', 'slug', 'name'])->find($categoryId);
    if (!$category) {
      return;
    }

    // "Thực phẩm khác" luôn hiển thị tất cả sản phẩm.
    if ($category->slug === 'thuc-pham-khac') {
      return;
    }

    $wantedNames = self::CATEGORY_PRODUCT_MAP[$category->slug] ?? null;

    // Nếu danh mục không nằm trong mapping, fallback về category_id như cũ.
    if (!is_array($wantedNames) || count($wantedNames) === 0) {
      $query->where('category_id', $categoryId);
      return;
    }

    $query->whereIn('name', $wantedNames);
  }
}
