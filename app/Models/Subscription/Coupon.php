<?php

namespace App\Models\Subscription\Coupon;

use App\Models\Concerns\HasFilter;
use App\Models\Concerns\Relationship\HasCreator;
use App\Models\User;
use App\Models\Landlord\Plan\Plan;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subscription\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory, HasFilter, HasCreator;

    protected $fillable = [
        "code",
        "discount",
        "discount_type",
        "times_used",
        "max_usage",
        "is_active",
        "user_id", // Specific to user
        "plan_id", // Specific to plan
        "tenant_id", // Specific to tenant
        "created_by"
    ];


    // Coupon valid or not
    public function isValid($plan): bool
    {
        // Check if already used by this tenant
        $alreadyUsed = tenant()->coupons()
            ->where("code", $this->code)
            ->exists();

        if ($alreadyUsed)
            return false;

        $valid = $this->times_used < $this->max_usage && $this->is_active;

        // If the coupon is set for specific user only
        if ($this->user_id) {
            if (auth()->id() !== $this->user_id) {
                $valid = false;

            }

        } else if ($this->plan_id) {
            // If the coupon is set for specific plan only
            if ($plan->id !== $this->plan_id) {
                $valid = false;

            }

        } else if ($this->tenant_id) {
            // If the coupon is set for specific plan only
            if (tenant()->id !== $this->tenant_id) {
                $valid = false;

            }

        }

        return $valid;

    }

    public function getDiscountAmount(float|int $totalAmount): float|int
    {
        $discount = $this->discount;
        if ($this->discount_type === "percentage") {
            $discount = ($totalAmount * $this->discount) / 100;

        }

        return max(0, $discount);

    }

    // For which plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);

    }

    // For which user
    public function user()
    {
        return $this->belongsTo(User::class);

    }

    // Redeemed subscriptions
    public function subscriptions()
    {
        return $this->belongsToMany(related: Subscription::class);

    }

}
