<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@ticketing.com'],
            [
                'name' => 'System Admin',
                'email' => 'admin@ticketing.com',
                'phone' => '254700000000',
                'national_id' => 'ADMIN001',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'password_changed_at' => now(),
            ]
        );
    }
}