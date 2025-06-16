<?php

use App\Models\Subscription\Plan;
use App\Models\Subscription\Module;
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
        Schema::create('module_plan', function (Blueprint $table) {
            $table->foreignIdFor(Module::class)
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignIdFor(Plan::class)
            ->constrained()
            ->cascadeOnDelete();

            $table->primary(['plan_id', 'module_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_plan');
    }
};
