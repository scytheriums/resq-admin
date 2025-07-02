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
        Role::truncate();
        Permission::truncate();
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

            // Driver Permissions
            ['name' => 'Create Driver', 'slug' => 'create-driver'],
            ['name' => 'Read Driver', 'slug' => 'read-driver'],
            ['name' => 'Update Driver', 'slug' => 'update-driver'],
            ['name' => 'Delete Driver', 'slug' => 'delete-driver'],


            // Ambulance Type Permissions
            ['name' => 'Create Ambulance Type', 'slug' => 'create-ambulance-type'],
            ['name' => 'Read Ambulance Type', 'slug' => 'read-ambulance-type'],
            ['name' => 'Update Ambulance Type', 'slug' => 'update-ambulance-type'],
            ['name' => 'Delete Ambulance Type', 'slug' => 'delete-ambulance-type'],

            // Purpose Permissions
            ['name' => 'Create Purpose', 'slug' => 'create-purpose'],
            ['name' => 'Read Purpose', 'slug' => 'read-purpose'],
            ['name' => 'Update Purpose', 'slug' => 'update-purpose'],
            ['name' => 'Delete Purpose', 'slug' => 'delete-purpose'],

            // Additional Service Permissions
            ['name' => 'Create Additional Service', 'slug' => 'create-additional-service'],
            ['name' => 'Read Additional Service', 'slug' => 'read-additional-service'],
            ['name' => 'Update Additional Service', 'slug' => 'update-additional-service'],
            ['name' => 'Delete Additional Service', 'slug' => 'delete-additional-service'],

            // Destination Permissions
            ['name' => 'Create Destination', 'slug' => 'create-destination'],
            ['name' => 'Read Destination', 'slug' => 'read-destination'],
            ['name' => 'Update Destination', 'slug' => 'update-destination'],
            ['name' => 'Delete Destination', 'slug' => 'delete-destination'],

            // Setting Permissions
            ['name' => 'Create Setting', 'slug' => 'create-setting'],
            ['name' => 'Read Setting', 'slug' => 'read-setting'],
            ['name' => 'Update Setting', 'slug' => 'update-setting'],
            ['name' => 'Delete Setting', 'slug' => 'delete-setting'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign all permissions to the admin role
        $adminRole->permissions()->sync(Permission::pluck('id'));
    }
}
