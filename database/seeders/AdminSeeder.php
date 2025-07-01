<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@resqin.com',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        // Create additional admin users
        Admin::create([
            'name' => 'John Doe',
            'email' => 'john@resqin.com',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        Admin::create([
            'name' => 'Jane Smith',
            'email' => 'jane@resqin.com',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);
    }
}
