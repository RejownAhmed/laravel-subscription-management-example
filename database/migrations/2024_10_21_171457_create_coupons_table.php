<?php

use App\Models\Tenant\Tenant;
use App\Models\User;
use App\Models\Subscription\Plan;
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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique();
            $table->foreignIdFor(Plan::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete(); // Can be set for specific plan
            $table->foreignIdFor(User::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete(); // Can be set for specific user
            $table->foreignIdFor(Tenant::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete(); // Can be set for specific Tenant
            $table->double("discount");
            $table->enum("discount_type", ["fixed", "percentage"]);
            $table->integer("times_used")->default(0);
            $table->integer("max_usage")->default(1);
            $table->boolean("is_active")->default(1);
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
        Schema::dropIfExists('coupons');
    }
};
