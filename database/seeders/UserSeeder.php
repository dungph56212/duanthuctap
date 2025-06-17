<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'tiendung08102005@gmail.com',
            'password' => Hash::make('Dung08102005#'),
            'phone' => '+84123456789',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create regular users
        User::factory(50)->create();
    }
}
