<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand_name',
        'description',
        'price',
        'condition',
        'image_path',
        'shipping_postal_code',
        'shipping_address',
        'shipping_building',
    ];

    // 出品者（items.user_id）
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // カテゴリ（N:M / 中間: category_items）
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_items');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // purchases.item_id が UNIQUE → 最大1件
    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class);
    }
}
