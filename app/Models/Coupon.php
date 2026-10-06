<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend',
        'max_discount',
        'usage_limit',
        'user_limit',
        'used_count',
        'start_date',
        'end_date',
        'status',
        'description',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'user_limit' => 'integer',
        'used_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Validate coupon eligibility and calculate discount.
     */
    public function calculateDiscount(float $subtotal, ?int $userId = null, ?string $email = null, ?string $phone = null): array
    {
        if (!$this->status) {
            return ['valid' => false, 'message' => 'This coupon is currently inactive.'];
        }

        $today = Carbon::today();

        if ($this->start_date && $today->lt($this->start_date)) {
            return ['valid' => false, 'message' => 'This coupon is not active yet (starts on ' . $this->start_date->format('M d, Y') . ').'];
        }

        if ($this->end_date && $today->gt($this->end_date)) {
            return ['valid' => false, 'message' => 'This coupon has expired on ' . $this->end_date->format('M d, Y') . '.'];
        }

        // Overall usage limit
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'This coupon has reached its maximum total usage limit.'];
        }

        // Minimum purchase requirement
        if ($this->min_spend !== null && $subtotal < (float) $this->min_spend) {
            return ['valid' => false, 'message' => 'Minimum order amount to apply this coupon is BDT ' . number_format($this->min_spend, 0) . '.'];
        }

        // Per-user usage limit check
        if ($this->user_limit > 0) {
            $userUsageQuery = $this->usages();
            $hasIdentifier = false;

            $userUsageQuery->where(function ($q) use ($userId, $email, $phone, &$hasIdentifier) {
                if ($userId) {
                    $q->orWhere('user_id', $userId);
                    $hasIdentifier = true;
                }
                if (!empty($email)) {
                    $q->orWhere('email', strtolower(trim($email)));
                    $hasIdentifier = true;
                }
                if (!empty($phone)) {
                    $q->orWhere('phone', trim($phone));
                    $hasIdentifier = true;
                }
            });

            if ($hasIdentifier) {
                $userUsageCount = $userUsageQuery->count();
                if ($userUsageCount >= $this->user_limit) {
                    return [
                        'valid' => false,
                        'message' => "You have already reached the maximum usage limit ({$this->user_limit} times) for this coupon."
                    ];
                }
            }
        }

        // Calculate discount amount
        $discount = 0.0;
        if ($this->type === 'percent') {
            $discount = round(($subtotal * (float) $this->value) / 100, 2);
            if ($this->max_discount !== null && $discount > (float) $this->max_discount) {
                $discount = (float) $this->max_discount;
            }
        } else {
            // Fixed discount
            $discount = min((float) $this->value, $subtotal);
        }

        return [
            'valid' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => $discount,
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'coupon' => $this,
        ];
    }
}
