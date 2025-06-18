<?php

namespace App\Models\Concerns\Subscription;

use App\Models\Subscription\Plan;
use App\Models\Subscription\Subscription;
use Carbon\Carbon;

trait HasSubscription
{
    use HasCoupons;

    public function currentSubscription() {
        return $this->subscriptions()
            ->whereNull("ended_at")
            ->whereNull("cancelled_at")
            ->first();

    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

}
