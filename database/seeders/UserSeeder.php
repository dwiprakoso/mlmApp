<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'phone' => '08123456789',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);
        $admin->assignRole('admin');

        // Member User 1
        $member1 = User::create([
            'name' => 'Member Satu',
            'phone' => '08123456788',
            'email' => 'member1@example.com',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);
        $member1->assignRole('member');
    }
}
