<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $viewConfigurationsPermission = Permission::create(['name' => 'view configurations']);

        // Create admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo($viewConfigurationsPermission);

        // Create farmer role
        Role::create(['name' => 'farmer']);
    }
}
