<?php

namespace App\Models\Tenant;

use App\Models\Concerns\Subscription\HasSubscription;
use App\Models\User;
use App\Models\Auth\Role;
use App\Models\Lead\Lead;
use App\Models\Email\SentEmail;
use App\Models\Department\Department;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\Relationship\HasStatus;
use App\Models\EmployeeProfile\EmployeeProfile;

class Tenant extends Model
{
    use HasStatus, HasSubscription;

    protected $hidden = ['pivot'];

    protected $fillable = [
        'slug',
        'name',
        'amount',
        'status_id',
        'about',
        'email',
        'phone',
        'website',
        'location',
        'is_default',
        'is_redirect'
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_user', 'tenant_id', 'user_id');
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Role::class);

    }

    // public function departments(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(Department::class);
    // }

    // public function employeeProfiles(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(EmployeeProfile::class);
    // }

    // public function leads(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(Lead::class);
    // }

    public function sentEmails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SentEmail::class);
    }

    public function seedRoles() {
        $this->roles()->insert([
            [
                'name' => 'manager',
                'label' => 'Manager',
                'tenant_id' => $this->id,
                'created_by' => null //This should be null for indicating default
            ],
            [
                'name' => 'employee',
                'label' => 'Employee',
                'tenant_id' => $this->id,
                'created_by' => null //This should be null for indicating default
            ],
        ]);
    }

    public static function findBySlug($param): Model|\Illuminate\Database\Eloquent\Builder|null
    {
        return self::query()->where('slug', $param)->first();
    }

    // Handling tenant instance

    public function makeCurrent(): self
    {
        if (optional(static::getCurrent())->id === $this->id) {
            return $this;
        }

        static::forgetCurrent();
        app()->instance('currentTenant', $this);

        return $this;
    }

    public static function getCurrent()
    {
        if (!app()->has('currentTenant')) {
            return null;
        }

        return app('currentTenant');
    }

    public static function forgetCurrent()
    {
        $currentTenant = static::getCurrent();

        if (is_null($currentTenant)) {
            return null;
        }

        app()->forgetInstance('currentTenant');

        return $currentTenant;
    }

}
