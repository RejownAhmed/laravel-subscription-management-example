<?php

namespace App\Http\Controllers\Tenant\Subscription;

use App\Models\Subscription\Plan;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index() {
        // Return all publicly available plans
        return Plan::active()
            ->public()
            ->get();

    }
}
