<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'url',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('uploads/' . $this->image))) {
            return asset('uploads/' . $this->image);
        }

        return asset('uploads/otobi_brand_otobi.jpg');
    }

    public function getTargetUrlAttribute(): ?string
    {
        if (!$this->url) {
            return null;
        }

        $trimmed = trim($this->url);

        if (
            str_starts_with($trimmed, 'http://') ||
            str_starts_with($trimmed, 'https://') ||
            str_starts_with($trimmed, '/') ||
            str_starts_with($trimmed, '#')
        ) {
            return $trimmed;
        }

        return 'https://' . $trimmed;
    }
}
