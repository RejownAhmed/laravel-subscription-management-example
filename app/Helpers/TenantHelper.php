<?php

use App\Models\Tenant\Tenant;

if (!function_exists('tenant')) {
    function tenant()
    {
        return Tenant::getCurrent();
        
    }
}
