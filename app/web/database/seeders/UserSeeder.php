<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Bocum\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Sugarcane Admin',
            'email' => 'admin@cvsu.edu.ph',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $admin->assignRole('admin');
        $admin->givePermissionTo('view configurations');

        // Create farmer user
        $farmer = User::factory()->create([
            'name' => 'Sugarcane Farmer',
            'email' => 'sugarcane@cvsu.edu.ph',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $farmer->assignRole('farmer');
    }
}
