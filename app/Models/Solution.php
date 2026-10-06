<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'category_id',
        'custom_url',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('uploads/' . $this->image))) {
            return asset('uploads/' . $this->image);
        }

        return asset('uploads/otobi_solution_home.jpg');
    }

    public function getTargetUrlAttribute(): string
    {
        if ($this->category) {
            return route('products.index', ['category' => $this->category->slug]);
        }

        if ($this->custom_url) {
            return $this->custom_url;
        }

        return route('products.index');
    }
}
