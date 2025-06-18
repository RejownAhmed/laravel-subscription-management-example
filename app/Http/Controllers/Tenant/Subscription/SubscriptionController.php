<?php

namespace App\Http\Controllers\Tenant\Subscription;

use App\Http\Controllers\Controller;
use App\Services\Subscription\SubscriptionService;
use DB;
use Illuminate\Http\Request;
use App\Models\Subscription\Plan;
use Log;

class SubscriptionController extends Controller
{
    protected SubscriptionService $service;

    public function __construct()
    {
        // Initialize the service
        $this->service = new SubscriptionService(tenant());

    }

    // Get all subscriptions for this tenant
    public function index(Request $request)
    {
        // Return all subscriptions history
        return success_response("Subscriptions Fetched Successfully.", [
            "subscriptions" => tenant()->subscriptions()->with("plan")->get()
        ]);

    }

    // Get current subscription for the tenant
    public function currentSubscription(Request $request)
    {
        return success_response("Subscription Fetched Successfully.", [
            "subscription" => tenant()->currentSubscription()
        ]);

    }

    /**
     * NOTE: If using redirection flow (Direct payment via Stripe of other payment methods)
     * instead of in app wallet recharge
     * The new subscription store should happen in a redirection callback method
     * This method should only create the charge to the provider
     * And returns the payment page redirection url
     * On the other hand If using multi-currency or non USD
     * Convert the price to the desired currency first
     * Or let the payment method provider know in which currency the user should pay for
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $plan = Plan::query()->find($validated['plan_id']);
        // If the plan is not active or publicly available
        if (!$plan->isActive() || !$plan->isPublic()) {
            return error_response("Unauthorize action!", 403);

        }

        $tenant = tenant();
        $totalPrice = $plan->totalPriceWithTax();
        // Check if tenant has enough balance
        if ($tenant->amount < $totalPrice) {
            return error_response("Insufficient Balance", 400);

        }

        try {
            DB::beginTransaction();
            // Charge the amount from the tenant wallet here
            $tenant->update([
                "amount" => $tenant->amount -= $totalPrice
            ]);

            // Create the new subscription
            $subscription = $this->service->storeNewSubscription($plan);

            DB::commit();

            return success_response("Subscribed Successfully!", [
                "subscription" => $subscription
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e);

            return error_response("Failed to subscribe to the plan", 500);
        }
    }

    // Renew a subscription or change the plan
    // NOTE: Checkout the note of store method
    public function renewOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $plan = Plan::query()->find($validated['plan_id']);

        // If the plan is not active
        if(!$plan->isActive()) return error_response("Plan not found!", 404);

        $currentSubscription = $this->service->getCurrentSubscription();

        /**
         * If plan is currently subscribed
         * check if current subscription is renewable
         *
         */
        if ($plan->id === $currentSubscription->plan_id) {
            if (!$currentSubscription->canBeRenewed()) {
                return error_response("Invalid request! This subscription cannot be renewed.", 400);

            }

        } else {
            // Else check if the plan is private
            // If so do not let the user subscribe it
            if(!$plan->isPublic()) return error_response("Unauthorized action!", 403);

        }

        $tenant = tenant();
        $totalPrice = $plan->totalPriceWithTax();

        // Check if tenant has enough balance
        if ($tenant->amount < $totalPrice) {
            return error_response("Insufficient Balance", 400);

        }

        try {
            DB::beginTransaction();
            // Charge the amount from the tenant wallet here
            $tenant->update([
                "amount" => $tenant->amount -= $totalPrice
            ]);

            // Store the new subscription
            $subscription = $this->service->storeNewSubscription($plan);

            DB::commit();

            return success_response("Subscription renewed Successfully!", [
                "subscription" => $subscription

            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // Keep the error log
            Log::error($e);
            // Return error message to client
            return error_response("Failed to renew subscription!", 500);

        }

    }

}
