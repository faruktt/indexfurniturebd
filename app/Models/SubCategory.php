<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function childCategories()
    {
        return $this->hasMany(ChildCategory::class)->where('status', true)->orderBy('order', 'asc');
    }

    public function allChildCategories()
    {
        return $this->hasMany(ChildCategory::class)->orderBy('order', 'asc');
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
        if ($this->category && $this->category->image && file_exists(public_path('uploads/' . $this->category->image))) {
            return asset('uploads/' . $this->category->image);
        }
        return 'https://placehold.co/400x300/f3f4f6/333333?text=' . urlencode($this->name);
    }
}
