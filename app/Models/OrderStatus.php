<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_statuses';

    protected $fillable = [
        'name',
        'slug',
        'bg_color',
        'text_color',
        'order',
        'is_default',
        'status',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'boolean',
        'order' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($status) {
            if (empty($status->slug)) {
                $status->slug = Str::slug($status->name);
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'order_status', 'slug');
    }

    public function getBadgeHtmlAttribute(): string
    {
        $bg = $this->bg_color ?: '#f1f5f9';
        $text = $this->text_color ?: '#334155';

        return '<span style="background: ' . $bg . '; color: ' . $text . '; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">' . e($this->name) . '</span>';
    }
}
