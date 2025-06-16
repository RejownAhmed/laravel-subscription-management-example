<?php

namespace App\Models\Concerns\Public;

use App\Scopes\TenantScope;
use Illuminate\Support\Facades\Route;

/**
 * Public Tenant Route Means Any Route that can respond without any tenant specific scope or middleware bindings
 *
 */
trait CanBePublic
{
    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // If public route, disable some scopes
        if ($this->isPublicRoute()) {
            return $this->withoutGlobalScope(TenantScope::class)->where($field ?? $this->getRouteKeyName(), $value)->first();

        }

        return parent::resolveRouteBinding($value, $field);
    }


    private function isPublicRoute()
    {
        // Get the current route name
        $currentRouteName = Route::currentRouteName();

        // Check if the route name starts with "public"
        if (strpos($currentRouteName, 'public') === 0) {
            return true;
        }

        return false;
    }
}
