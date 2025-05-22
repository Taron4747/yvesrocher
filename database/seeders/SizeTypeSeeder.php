<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SizeType;

class SizeTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name_arm' => 'մլ',
                'name_ru' => 'мл',
                'name_en' => 'ml',
            ],
            [
                'name_arm' => 'գ',
                'name_ru' => 'г',
                'name_en' => 'g',
            ],
        ];

        foreach ($types as $type) {
            SizeType::updateOrCreate(
                ['name_en' => $type['name_en']], // prevent duplicates
                $type
            );
        }
    }
}
