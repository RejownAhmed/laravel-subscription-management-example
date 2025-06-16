<?php

namespace App\Models\Subscription;

use App\Enums\Currency;
use App\Models\Concerns\Relationship\HasCreator;
use App\Models\Status\Status;
use App\Models\Subscription\Concerns\HasTax;
use App\Models\Subscription\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subscription\Subscription;
use App\Models\Concerns\Relationship\HasStatus;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plan extends Model
{
    use HasStatus, HasTax, HasCreator;

    protected $fillable = [
        'title',
        'description',
        'tag',
        'price',
        'is_free',
        'is_public',
        'invoice_period',
        'invoice_interval',
        'trial_period',
        'trial_interval',
        'currency',
        'tax_id',
        'status_id',
        'created_by',
    ];

    protected $casts = [
        "currency" => Currency::class,
    ];
    
    // Active plans
    public function scopeActive($query)
    {
        $statusActive = Status::findByNameAndType("active", "plan");

        return $query->where("status_id", $statusActive->id);
    }
    public function isActive(): bool
    {
        $statusActive = Status::findByNameAndType("active", "plan");

        return $this->status_id == $statusActive->id;
    }

    // Free plans
    public function scopeFree($query)
    {
        return $query->where("is_free", 1);

    }
    public function isFree(): bool
    {
        return $this->is_free;

    }

    // Public plans
    public function scopePublic($query)
    {
        return $query->where("is_public", 1);

    }
    public function isPublic(): bool
    {
        return $this->is_public == 1;

    }

    // Trial
    public function hasTrial() {
        return $this->trial_period !== 0;

    }

    // Relationships
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class);

    }

    // Subscription
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);

    }

}
