<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant\Tenant;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_subscribe_to_plan()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a tenant and attach to user
        $tenant = Tenant::factory()->create();
        $tenant->users()->attach($user);

        // Create a plan
        $plan = Plan::factory()->create();

        // Subscribe tenant to the plan
        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'price' => $plan->price,
            'tax_amount' => 0,
            'is_trial' => false,
            'currency' => $plan->currency,
            'invoice_period' => $plan->invoice_period,
            'invoice_interval' => $plan->invoice_interval,
            'trial_period' => $plan->trial_period,
            'trial_interval' => $plan->trial_interval,
            'start_at' => now(),
            'status_id' => 1, // You may want to use a factory or a constant for this
            'created_by' => $user->id,
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);
    }
}
