<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'email',
        'phone',
        'country',
        'district',
        'area',
        'postal_code',
        'address',
        'delivery_method',
        'delivery_charge',
        'payment_method',
        'subtotal',
        'discount',
        'coupon_code',
        'grand_total',
        'order_status',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'delivery_charge' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'BDT ' . number_format($this->grand_total, 0);
    }

    public function orderStatusRecord()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status', 'slug');
    }

    public function getStatusBadgeAttribute(): string
    {
        static $cachedStatuses = null;
        if ($cachedStatuses === null) {
            $cachedStatuses = \App\Models\OrderStatus::all()->keyBy('slug');
        }

        if (isset($cachedStatuses[$this->order_status])) {
            $st = $cachedStatuses[$this->order_status];
            return '<span style="background: ' . $st->bg_color . '; color: ' . $st->text_color . '; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">' . e($st->name) . '</span>';
        }

        return match($this->order_status) {
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'processing' => '<span class="badge badge-info">Processing</span>',
            'shipped' => '<span class="badge" style="background:#f3e8ff; color:#7e22ce;">Shipped</span>',
            'delivered' => '<span class="badge badge-success">Delivered</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            default => '<span class="badge badge-secondary">' . ucfirst($this->order_status) . '</span>',
        };
    }
}
