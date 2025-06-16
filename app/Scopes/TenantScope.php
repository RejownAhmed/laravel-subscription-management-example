<?php

namespace App\Scopes;

use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\TenantNotFoundException;

class TenantScope implements Scope
{


    public function apply(Builder $builder, Model $model)
    {
        if (app()->runningInConsole()) {
            return true;
        }

        if (app()->runningUnitTests()) {
            return true;
        }

        // If super admin
       if (auth()->check()) {
            if (auth()->user()->is_admin) {
                return true;
            }
       }

        $builder->when(tenant(), function (Builder $builder) {
            $builder->where('tenant_id', tenant()->id);
        });

    }

}
