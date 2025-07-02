<?php

namespace App\Models\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRolesAndPermissions
{
    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Check if the user has a role.
     *
     * @param mixed ...$roles
     * @return bool
     */
    public function hasRole(...$roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the user has a permission through their roles.
     *
     * @param \App\Models\Permission|string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $permissionSlug = is_string($permission) ? $permission : $permission->slug;

        foreach ($this->roles as $role) {
            if ($role->permissions->contains('slug', $permissionSlug)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Assign a role to the user.
     *
     * @param string $role
     * @return mixed
     */
    public function assignRole($role)
    {
        $role = Role::where('slug', $role)->firstOrFail();
        return $this->roles()->syncWithoutDetaching($role);
    }
}
