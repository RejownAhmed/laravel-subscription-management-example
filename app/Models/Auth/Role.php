<?php

namespace App\Models\Auth;

use App\Models\Auth\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = [
        'name', 'label', 'tenant_id', 'created_by',
    ];

    protected $hidden = ['pivot'];

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }


    public function givePermissionTo(string|Permission|Collection $permission)
    {
        if (is_string($permission)) {
            $permission = Permission::whereName($permission)->firstOrFail();
            return $this->permissions()->attach($permission->id);

        } else if ($permission instanceof Permission) {
            return $this->permissions()->attach($permission->id);

        } else if ($permission instanceof Collection) {
            $permission->each(function ($permission) {
                $this->permissions()->attach($permission?->id);
                
            });

            return true;

        }
    }

    public function revokePermission(string|Permission $permission)
    {
        if (is_string($permission)) {
            $permission = Permission::whereName($permission)->firstOrFail();
            return $this->permissions()->detach($permission->id);

        } else if ($permission instanceof Permission) {
            return $this->permissions()->detach($permission->id);

        }
    }
}
