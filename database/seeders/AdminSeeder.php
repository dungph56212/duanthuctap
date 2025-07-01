<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo hoặc cập nhật tài khoản admin
        User::updateOrCreate(
            ['email' => 'bookstore@admin.com'],
            [
                'name' => 'BookStore Admin',
                'email' => 'bookstore@admin.com',
                'password' => Hash::make('admin123456'),
                'phone' => '0123456789',
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $this->command->info('✅ Tài khoản admin đã được tạo thành công!');
        $this->command->info('📧 Email: bookstore@admin.com');
        $this->command->info('🔑 Password: admin123456');
        $this->command->info('🌐 URL Admin: http://localhost:8000/admin/login');
    }
}
