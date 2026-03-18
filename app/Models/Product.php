<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug','category_id','description', 'price', 'stock', 'status', 'unit'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function CartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function firstImage()
    {
        return $this->hasOne(ProductImage::class)->orderBy('id', 'ASC');
    }

    public function getImageUrlAttribute(): string
    {
        $image = $this->firstImage?->image;
        if (! is_string($image) || $image === '') {
            // Public placeholder (doesn't depend on /storage symlink or APP_URL).
            return '/img/product/1.png';
        }

        $path = $image;
        $path = ltrim($path, '/');

        // Supported DB formats:
        // - filename only: "abc.jpg"
        // - public disk relative: "uploads/users/products/abc.jpg"
        // - public path: "storage/uploads/users/products/abc.jpg"
        if (str_starts_with($path, 'storage/')) {
            $publicPath = $path;
        } elseif (str_starts_with($path, 'uploads/')) {
            $publicPath = 'storage/' . $path;
        } else {
            $publicPath = 'storage/uploads/users/products/' . $path;
        }

        // Return a root-relative URL to avoid APP_URL host/port mismatches.
        return '/' . ltrim(self::encodeUrlPath($publicPath), '/');
    }

    protected static function encodeUrlPath(string $path): string
    {
        return implode('/', array_map('rawurlencode', explode('/', $path)));
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
