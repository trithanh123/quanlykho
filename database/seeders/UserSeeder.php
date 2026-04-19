<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Quản Trị',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'is_locked' => false,
        ]);

        User::create([
            'name' => 'Thành Thủ Kho',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'manager',
            'is_locked' => false,
        ]);

        User::create([
            'name' => 'Tài Xế Giao Hàng',
            'email' => 'driver@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'driver',
            'is_locked' => false,
        ]);
    }
}