<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'key' => 'app_name',
                'value' => 'My Application',
            ],
            [
                'key' => 'app_logo',
                'value' => '/images/logo.png',
            ],
            [
                'key' => 'app_description',
                'value' => 'This is a sample application built with Laravel',
            ],
            [
                'key' => 'bank_name',
                'value' => 'BRI',
            ],
            [
                'key' => 'bank_account_number',
                'value' => '1234567890',
            ],
            [
                'key' => 'account_name',
                'value' => 'John Doe',
            ],
            [
                'key' => 'payment_qr_code',
                'value' => '',
            ],
            [
                'key' => 'team_invite_presentation',
                'value' => '10',
            ],
            [
                'key' => 'header_text',
                'value' => 'Investasi pertambangan',
            ],
            [
                'key' => 'legal_name',
                'value' => 'PT RICH KINGDOM ID',
            ],
        ];

        foreach ($configs as $config) {
            Config::firstOrCreate(
                ['key' => $config['key']],
                ['value' => $config['value']]
            );
        }
    }
}
