<?php

use App\Enums\Currency;
use App\Models\Status\Status;
use App\Models\Tenant\Tenant;
use App\Models\Subscription\Plan;
use App\Enums\Subscription\Interval;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->double('price'); // Should stay static after the subscription has been taken
            $table->double('tax_amount')->default(0); // Should stay static after the subscription has been taken
            $table->boolean(column: 'is_trial')
                ->default(0);
            $table->string('currency')
                ->default(Currency::USD->value);
            // Invoicing Period
            $table->integer('invoice_period')->default(30);
            $table->enum('invoice_interval', Interval::values())
                ->default(Interval::DAY->value);
            // Trial Period
            $table->integer('trial_period')->default(0);
            $table->enum('trial_interval', Interval::values());
            // Start at
            $table->date('start_at');
            // If ended at
            $table->date('ended_at')->nullable();
            // If cancelled
            $table->date('cancelled_at')->nullable();

            $table->foreignIdFor(Plan::class)
                ->constrained(); // For reference
            // Right now it's tenant specific subscription
            // However, it can be user specific as needed
            // Just simply define relationship with User model instead of tenant
            // And update other codebase methods accordingly
            $table->foreignIdFor(Tenant::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Status::class)
                ->constrained();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');

    }
};
