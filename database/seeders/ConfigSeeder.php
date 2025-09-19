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
        ];

        foreach ($configs as $config) {
            Config::updateOrCreate(
                ['key' => $config['key']],
                ['value' => $config['value']]
            );
        }
    }
}
