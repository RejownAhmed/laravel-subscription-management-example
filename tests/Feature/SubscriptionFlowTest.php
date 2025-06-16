<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant\Tenant;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Subscription;
use App\Models\Status\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Enums\Currency;

class SubscriptionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_subscribe_to_plan()
    {
        // Create a user
        $user = User::factory()->create();
        $status = Status::findByNameAndType("active", "tenant");
        // Create a tenant and attach to user
        $tenant = Tenant::create([
            'slug' => 'test-tenant',
            'name' => 'Test Tenant',
            'status_id' => $status->id
        ]);

        $tenant->users()->attach($user);

        $status = Status::findByNameAndType("active", "plan");
        // Create a plan
        $plan = Plan::create([
            'title' => 'Test Plan',
            'price' => 10.0,
            'invoice_period' => 30,
            'invoice_interval' => 'day',
            'trial_period' => 0,
            'trial_interval' => 'day',
            'currency' => Currency::USD->value,
            'status_id' => $status->id,
        ]);

        $status = Status::findByNameAndType("active", "subscription");
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
            'status_id' => $status->id,
            'created_by' => $user->id,
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
        ]);
    }
}
