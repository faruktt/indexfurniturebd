<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
        'order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('uploads/' . $this->image))) {
            return asset('uploads/' . $this->image);
        }
        return 'https://placehold.co/600x600/ffffff/333333?text=Gallery';
    }
}
