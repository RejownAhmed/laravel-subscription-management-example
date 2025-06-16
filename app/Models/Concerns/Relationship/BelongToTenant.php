<?php

namespace App\Models\Concerns\Relationship;

use App\Scopes\TenantScope;
use App\Models\Tenant\Tenant;

trait BelongToTenant
{

    protected static function bootBelongToTenant(): void
    {
        if (!app()->runningInConsole()) {
            static::addGlobalScope(new TenantScope());

            static::creating(function ($model){
                if ($model->tenant_id) {
                    return $model;
                    
                } else {
                    $model->tenant_id = tenant()->id;

                }
            });

            static::saving(function ($model){
                if ($model->tenant_id) {
                    return $model;

                } else {
                    $model->tenant_id = tenant()->id;

                }
            });

        }
    }

    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
