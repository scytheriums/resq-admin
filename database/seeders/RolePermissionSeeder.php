<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $customerRole = Role::create(['name' => 'Customer', 'slug' => 'customer']);

        // Create Permissions
        $permissions = [
            // User Permissions
            ['name' => 'Create Users', 'slug' => 'create-users'],
            ['name' => 'Read Users', 'slug' => 'read-users'],
            ['name' => 'Update Users', 'slug' => 'update-users'],
            ['name' => 'Delete Users', 'slug' => 'delete-users'],

            // Role Permissions
            ['name' => 'Create Roles', 'slug' => 'create-roles'],
            ['name' => 'Read Roles', 'slug' => 'read-roles'],
            ['name' => 'Update Roles', 'slug' => 'update-roles'],
            ['name' => 'Delete Roles', 'slug' => 'delete-roles'],

            // Permission Permissions
            ['name' => 'Create Permissions', 'slug' => 'create-permissions'],
            ['name' => 'Read Permissions', 'slug' => 'read-permissions'],
            ['name' => 'Update Permissions', 'slug' => 'update-permissions'],
            ['name' => 'Delete Permissions', 'slug' => 'delete-permissions'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign all permissions to the admin role
        $adminRole->permissions()->sync(Permission::all());
    }
}
