<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'product_code',
        'category_id',
        'sub_category_id',
        'child_category_id',
        'price',
        'discount_price',
        'dimension',
        'warranty',
        'short_description',
        'description',
        'image',
        'stock',
        'is_featured',
        'is_popular',
        'cash_on_delivery',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'cash_on_delivery' => 'boolean',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function childCategory()
    {
        return $this->belongsTo(ChildCategory::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order', 'asc');
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->discount_price > 0 ? $this->discount_price : $this->price);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'BDT ' . number_format($this->effective_price, 0);
    }

    public function getFormattedRegularPriceAttribute(): string
    {
        return 'BDT ' . number_format($this->price, 0);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('uploads/' . $this->image))) {
            return asset('uploads/' . $this->image);
        }
        return 'https://placehold.co/600x600/ffffff/333333?text=' . urlencode($this->title);
    }
}
