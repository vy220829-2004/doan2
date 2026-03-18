<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): string
    {
        $path = (string) ($this->image ?? '');
        $path = ltrim($path, '/');

        if ($path === '') {
            return '';
        }

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

        return '/' . ltrim(self::encodeUrlPath($publicPath), '/');
    }

    protected static function encodeUrlPath(string $path): string
    {
        return implode('/', array_map('rawurlencode', explode('/', $path)));
    }
}
