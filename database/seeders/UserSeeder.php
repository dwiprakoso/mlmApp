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
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'phone' => '08123456789',
                'password' => Hash::make('password'),
                'status' => 'active'
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
        $admin1 = User::updateOrCreate(
            ['email' => 'admin1@example.com'],
            [
                'name' => 'Administrator',
                'phone' => '6281266818738',
                'password' => Hash::make('admin81'),
                'status' => 'active'
            ]
        );
        if (!$admin1->hasRole('admin')) {
            $admin1->assignRole('admin');
        }

        $member1 = User::updateOrCreate(
            ['email' => 'member1@example.com'],
            [
                'name' => 'Member Satu',
                'phone' => '08123456788',
                'password' => Hash::make('password'),
                'status' => 'active'
            ]
        );
        if (!$member1->hasRole('member')) {
            $member1->assignRole('member');
        }
    }
}
