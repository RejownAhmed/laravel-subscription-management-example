<?php

namespace App\Models\Subscription;

use App\Models\Tenant\Tenant;
use App\Models\Subscription\Plan;
use App\Models\Tenant\TenantBaseModel;
use App\Models\Subscription\Coupon\Coupon;
use App\Models\Concerns\Relationship\HasStatus;
use App\Models\Concerns\Relationship\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends TenantBaseModel
{
    use HasFactory, HasStatus, HasCreator;

    protected $fillable = [
        'price',
        'tax_amount',
        'is_trial',
        'invoice_period',
        'invoice_interval',
        'trial_period',
        'trial_interval',
        'start_at',
        'plan_id',
        'currency',
        'tenant_id',
        'created_by',
        'status_id',
    ];

    protected $casts = [
        "start_at" => "datetime"

    ];

    // Trial Related
    public function trialEndDate() {
        $method = 'add' . ucfirst($this->trial_interval) . 's';
        $this->end = $this->start_at->{$method}($this->period);

    }

    public function isTrialOver() {
        return $this->trialEndDate()->isPast();

    }

    // Invoice Related
    public function endDate() {
        $method = 'add' . ucfirst($this->trial_interval) . 's';
        $this->end = $this->start_at->{$method}($this->period);

    }

    public function shouldRenew() {
        return $this->endDate()->isPast();

    }

    // Relationships
    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Plan::class);

    }

    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);

    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class);

    }

}
