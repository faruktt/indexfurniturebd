<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'section',
        'content',
        'order',
        'status',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function getSectionNameAttribute(): string
    {
        return match ($this->section) {
            'company' => 'The Company',
            'information' => 'More Information',
            default => ucfirst($this->section),
        };
    }
}
