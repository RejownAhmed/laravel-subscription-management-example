<?php

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Tenant\Tenant;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Roles Table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->index();
            $table->string('label'); //E.g Manager
            $table->ForeignIdFor(Tenant::class)
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Can be tenant specific
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();
        });

        //Permissions Table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250)
                ->unique()
                ->index(); // E.g edit_item
            $table->string('group_name', 250);
            $table->enum('type', ['app', 'tenant'])
                ->default('app');

        });

        //Permission role PIVOT Relationship
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignIdFor(Permission::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Role::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['permission_id', 'role_id']);

        });

        //Role user PIVOT Relationship
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignIdFor(Role::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Tenant::class)
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Can be tenant specific

            $table->primary(['role_id', 'user_id']);

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
    }
};
