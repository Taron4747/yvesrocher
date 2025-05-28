<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'delivery_price',
                'value' => '1000',
            ],
            [
                'name' => 'delivery_discount',
                'value' => '15000',
            ],
        ];

        foreach ($types as $type) {
            Setting::updateOrCreate(
                ['name' => $type['name']], // prevent duplicates
                $type
            );
        }
    }
}
