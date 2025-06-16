<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_user', function (Blueprint $table) {
            $table->ForeignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->ForeignIdFor(\App\Models\Tenant\Tenant::class)->constrained()->cascadeOnDelete();

//            $table->ForeignIdFor(User::class)->constrained();
//            $table->ForeignIdFor(\App\Models\Tenant\Tenant::class)->constrained();
            $table->primary(['user_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_user');
    }
};
