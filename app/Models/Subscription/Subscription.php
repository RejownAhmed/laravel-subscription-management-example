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
        'ended_at',
        'cancelled_at',
        'plan_id',
        'currency',
        'tenant_id',
        'created_by',
        'status_id',
    ];

    protected $casts = [
        "start_at" => "datetime"

    ];

    // Is Cancelled
    public function isCancelled() {
        return !!$this->cancelled_at;

    }

    // Is Cancelled
    public function isEnded() {
        return !!$this->ended_at;

    }
    // Trial End date
    public function trialEndDate() {
        $method = 'add' . ucfirst($this->trial_interval) . 's';
        $this->end = $this->start_at->{$method}($this->period);

    }

    // Invoice End date
    public function invoiceEndDate() {
        $method = 'add' . ucfirst($this->trial_interval) . 's';
        $this->end = $this->start_at->{$method}($this->period);

    }

    // Is renewable
    public function canBeRenewed() {
        // If the subscription has ended or cancelled and the subscribed plan is active
        return (!!$this->ended_at || !!$this->cancelled_at);

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
