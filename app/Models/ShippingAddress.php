<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingAddress extends Model
{
    protected $fillable = ['user_id', 'full_name', 'phone', 'address', 'city', 'default'];

    public function getDefaultAttribute($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if ($value === null) {
            return false;
        }

        $normalized = strtolower(trim((string) $value));
        return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
    }

    public function setDefaultAttribute($value): void
    {
        $this->attributes['default'] = $value ? 1 : 0;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
