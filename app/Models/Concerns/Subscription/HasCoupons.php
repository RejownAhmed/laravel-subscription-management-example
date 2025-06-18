<?php

namespace App\Models\Concerns\Subscription;

use App\Models\Subscription\Coupon\Coupon;

trait HasCoupons
{
    public function usedCoupons()
    {
        return $this->belongsToMany(Coupon::class);

    }

    public function allowedCoupons()
    {
        return $this->hasMany(Coupon::class);

    }

}
