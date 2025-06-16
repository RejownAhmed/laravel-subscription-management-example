<?php

use App\Providers\AuthServiceProvider;
use App\Providers\HelperServiceProvider;
use App\Providers\TenantServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    AuthServiceProvider::class,
    HelperServiceProvider::class,
    TenantServiceProvider::class,
];
