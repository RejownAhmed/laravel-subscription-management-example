<?php

use App\Enums\Currency;
use App\Models\Status\Status;
use App\Enums\Subscription\Interval;
use App\Models\Subscription\Tax;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('tag')->nullable();
            $table->double('price')->default(0);
            $table->string('currency')
                ->default(Currency::USD->value);
            $table->boolean('is_free')->default(0);
            $table->boolean('is_public')->default(1); // Can be private
            // Invoicing Period
            $table->integer('invoice_period')->default(30);
            $table->enum('invoice_interval', Interval::values())
                ->default(Interval::DAY->value);
            // Trial Period
            $table->integer('trial_period')->default(0);
            $table->enum('trial_interval', Interval::values())
                ->default(Interval::DAY->value);
            // Tax
            $table->foreignIdFor(Tax::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignIdFor(Status::class)->constrained();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
