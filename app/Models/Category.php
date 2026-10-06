<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'icon',
        'is_popular_home',
        'is_popular_office',
        'is_featured',
        'status',
        'order',
    ];

    protected $casts = [
        'is_popular_home' => 'boolean',
        'is_popular_office' => 'boolean',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class)->where('status', true)->orderBy('order', 'asc');
    }

    public function allSubCategories()
    {
        return $this->hasMany(SubCategory::class)->orderBy('order', 'asc');
    }

    public function childCategories()
    {
        return $this->hasMany(ChildCategory::class)->where('status', true)->orderBy('order', 'asc');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('uploads/' . $this->image))) {
            return asset('uploads/' . $this->image);
        }
        return 'https://placehold.co/400x300/f3f4f6/333333?text=' . urlencode($this->name);
    }
}
