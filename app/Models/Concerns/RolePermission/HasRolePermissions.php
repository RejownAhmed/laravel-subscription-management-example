<?php

namespace App\Models\Concerns\RolePermission;

use App\Models\Auth\Role;
use App\Models\Tenant\Tenant;
use Illuminate\Database\Eloquent\Collection;

trait HasRolePermissions
{

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    public function role()
    {
        return $this->roles()->toOne();
    }

    public function permissions()
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten(1);
    }


    public function assignRole($roles)
    {
        // If requesting user is not app admins(Landlords)
        if (!auth()->user()->is_admin) {
            return $this->assignTenantSpecificRole($roles);
        }

        if ($roles instanceof Collection) {
            $roles->each(function ($role) {
                $this->roles()->attach($role?->id);
            });

            return true;

        }
        if (is_array($roles)) {
            collect($roles)->each(function ($role) {
                $role = Role::whereName($role['name'])->firstOrFail();
                $this->roles()->attach($role?->id);
            });

            return true;

        } else if ($roles instanceof Role) {
            return $this->roles()->attach($roles->id);

        } else if (is_string($roles)) {
            $role = Role::whereName($roles)->firstOrFail();
            return $this->roles()->attach($role?->id);

        }

    }

    public function detachRole($roles)
    {
        // If requesting user is not app admins(Landlords)
        if (!auth()->user()->is_admin) {
            return $this->detachTenantSpecificRole($roles);
        }

        if ($roles instanceof Collection) {
            $roles->each(function ($role) {
                $role = Role::whereName($role['name'])->firstOrFail();
                $this->roles()->detach($role?->id);
            });

            return true;

        }
        if (is_array($roles)) {
            collect($roles)->each(function ($role) {
                $role = Role::whereName($role['name'])->firstOrFail();
                $this->roles()->detach($role?->id);
            });

            return true;

        } else if ($roles instanceof Role) {
            return $this->roles()->detach($roles->id);

        } else if (is_string($roles)) {
            $role = Role::whereName($roles)->firstOrFail();
            return $this->roles()->detach($role?->id);

        }

    }


    public function hasRole($roles)
    {
        // If requesting user is not app admins(Landlords)
        if (!auth()->user()->is_admin) {
            return $this->hasTenantSpecificPermission($roles);
        }

        if (is_string($roles))
            return $this->roles->contains('name', $roles);

        if (is_int($roles))
            return $this->roles->contains('id', $roles);

        if ($roles instanceof Role)
            return $this->roles->contains('id', $roles->id);

        if (is_array($roles)) {
            foreach ($roles as $key => $role) {
                if ($this->hasRole($role))
                    return true;
                break;
            }
            return false;
        }

        return $roles->intersect($this->roles)->isNotEmpty();

    }


    public function hasPermission($permissions)
    {
        // If requesting user is not app admins(Landlords)
        if (!auth()->user()->is_admin) {
            return $this->hasTenantSpecificPermission($permissions);
        }

        $allUserPermissions = $this->permissions();

        if (is_string($permissions))
            return $allUserPermissions->contains('name', $permissions);

        if (is_int($permissions))
            return $allUserPermissions->contains('id', $permissions);

        if ($permissions instanceof Role)
            return $allUserPermissions->contains('id', $permissions->id);

        if (is_array($permissions)) {
            foreach ($permissions as $key => $permission) {
                if ($this->hasRole($permission))
                    return true;
                break;
            }
            return false;
        }

        return $permissions->intersect($allUserPermissions)->isNotEmpty();

    }


    // Tenant specific
    // This methods are used to
    // Attach, check and update
    // Tenant specific roles and permissions

    // Attach a role to this user
    // For this tenant
    public function assignTenantSpecificRole($roles)
    {
        $tenant = tenant();

        if ($roles instanceof Collection) {
            $roles->each(function ($role) use($tenant) {
                $this->roles()->attach($role->id, ['tenant_id' => $tenant->id]);
            });

            return true;

        }
        if (is_array($roles)) {
            collect($roles)->each(function ($role) use($tenant) {
                $role = Role::whereName($role['name'])
                    ->where('tenant_id', $tenant->id)
                    ->firstOrFail();
                $this->roles()->attach($role->id, ['tenant_id' => $tenant->id]);
            });

            return true;

        } else if ($roles instanceof Role) {
            return $this->roles()->attach($roles->id, ['tenant_id' => $tenant->id]);

        } else if (is_string($roles)) {
            $role = Role::whereName($roles)
                ->where('tenant_id', $tenant->id)
                ->firstOrFail();
            return $this->roles()->attach($role->id, ['tenant_id' => $tenant->id]);

        }

    }

    // Detach a role from this user
    // For this tenant
    public function detachTenantSpecificRole($roles)
    {
        $tenant = Tenant::getCurrent();

        if ($roles instanceof Collection) {
            $roles->each(function ($role) use($tenant) {
                $role = Role::whereName($role['name'])
                    ->where('tenant_id', $tenant->id)
                    ->firstOrFail();
                $this->roles()->detach($role->id);
            });

            return true;

        }
        if (is_array($roles)) {
            collect($roles)->each(function ($role) use($tenant){
                $role = Role::whereName($role['name'])
                    ->where('tenant_id', $tenant->id)
                    ->firstOrFail();
                $this->roles()->detach($role->id);
            });

            return true;

        } else if ($roles instanceof Role) {
            return $this->roles()->detach($roles->id);

        } else if (is_string($roles)) {
            $role = Role::whereName($roles)
                ->where('tenant_id', $tenant->id)
                ->firstOrFail();

            return $this->roles()->detach($role->id);

        }

    }

    // Check user roles
    // For this tenant
    public function hasTenantSpecificRole($roles)
    {
        $tenantSpecificRoles = $this->getTenantSpecificRoles();

        if (is_string($roles))
            return $tenantSpecificRoles->contains('name', $roles);

        if (is_int($roles))
            return $tenantSpecificRoles->contains('id', $roles);

        if ($roles instanceof Role)
            return $tenantSpecificRoles->contains('id', $roles->id);

        if (is_array($roles)) {
            foreach ($roles as $key => $role) {
                if ($this->hasTenantSpecificRole($role))
                    return true;
                break;
            }
            return false;
        }

        return $roles->intersect($tenantSpecificRoles)->isNotEmpty();

    }


    // Check user permissions
    // For this tenant
    public function hasTenantSpecificPermission($permissions)
    {
        $tenantSpecificPermissions = $this->getTenantSpecificPermissions();

        if (is_string($permissions))
            return $tenantSpecificPermissions->contains('name', $permissions);

        if (is_int($permissions))
            return $tenantSpecificPermissions->contains('id', $permissions);

        if ($permissions instanceof Role)
            return $tenantSpecificPermissions->contains('id', $permissions->id);

        if (is_array($permissions)) {
            foreach ($permissions as $key => $permission) {
                if ($this->hasRole($permission))
                    return true;
                break;
            }
            return false;
        }

        return $permissions->intersect($tenantSpecificPermissions)->isNotEmpty();

    }

    // Get user roles
    // For this tenant
    public function getTenantSpecificRoles(): Collection
    {
        $tenant = Tenant::getCurrent();

        return $this->roles()
            ->wherePivot('tenant_id', $tenant->id)
            ->get();
    }

    // Get user permissions
    // For this tenant
    public function getTenantSpecificPermissions()
    {
        $tenant = Tenant::getCurrent();

        return $this->roles()
            ->wherePivot('tenant_id', $tenant->id)
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten(1);
    }


}
