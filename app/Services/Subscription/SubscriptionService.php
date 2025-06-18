<?php

namespace App\Services\Subscription;

use App\Models\Subscription\Subscription;
use App\Models\Tenant\Tenant;
use App\Services\BaseService;
use Illuminate\Support\Carbon;
use App\Models\Subscription\Plan;
use Illuminate\Validation\UnauthorizedException;

final class SubscriptionService extends BaseService
{
    public Subscription|null $currentSubscription = null;

    public function __construct(Tenant $tenant) {
        $this->setModel($tenant);

    }

    public function cancelCurrentSubscription(?Carbon $dateTime = null) {
        if(!$this->currentSubscription) {
            $this->currentSubscription = $this->model->currentSubscription();

        }

        $this->currentSubscription->update([
            "cancelled_at" => $dateTime ?: now()
        ]);

        return $this;

    }

    public function storeNewSubscription(Plan $plan, ?Carbon $dateTime = null) {
        $time = $dateTime ?: now();
        // Store the renewed the subscription
        $this->currentSubscription = $this->model->subscriptions()->create([
            'plan_id' => $plan->id,
            'price' => $plan->price,
            'tax_amount' => $plan->taxAmount(),
            'is_trial' => $plan->hasTrial(),
            'currency' => $plan->currency,
            'invoice_period' => $plan->invoice_period,
            'invoice_interval' => $plan->invoice_interval,
            'trial_period' => $plan->trial_period,
            'trial_interval' => $plan->trial_interval,
            'start_at' => $time,
            'created_by' => auth()->id(),
        ]);

        return $this;

    }

    public function useSubscription(Subscription $subscription) {
        $this->currentSubscription = $subscription;

        return $this;
    }

    public function getCurrentSubscription() {
        if(!$this->currentSubscription) {
            $this->currentSubscription = $this->model->currentSubscription();

        }

        return $this->currentSubscription;

    }

}
