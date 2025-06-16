<?php

namespace App\Providers;

use App\Domain\TenantFinder;
use App\Models\Tenant\Tenant;
use App\Domain\DomainTenantFinder;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            // Set current tenant if sent from frontend
            $slug = request()->header('X-tenant');
            $tenant = Tenant::findBySlug($slug);
            optional($tenant)->makeCurrent();
        }
    }

}
