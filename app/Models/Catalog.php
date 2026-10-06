<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Catalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'pdf_file',
        'description',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($catalog) {
            if (empty($catalog->slug)) {
                $catalog->slug = Str::slug($catalog->title);
            }
        });
    }

    public function getPdfUrlAttribute(): string
    {
        if ($this->pdf_file && file_exists(public_path('uploads/' . $this->pdf_file))) {
            return asset('uploads/' . $this->pdf_file);
        }
        return asset('uploads/' . $this->pdf_file);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)->orderBy('order', 'asc')->orderBy('id', 'desc');
    }
}
