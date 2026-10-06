<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sub_category_id',
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

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
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
        if ($this->subCategory && $this->subCategory->image && file_exists(public_path('uploads/' . $this->subCategory->image))) {
            return asset('uploads/' . $this->subCategory->image);
        }
        if ($this->category && $this->category->image && file_exists(public_path('uploads/' . $this->category->image))) {
            return asset('uploads/' . $this->category->image);
        }
        return 'https://placehold.co/400x300/f3f4f6/333333?text=' . urlencode($this->name);
    }
}
