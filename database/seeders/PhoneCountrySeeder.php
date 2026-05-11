<?php

namespace Database\Seeders;

use App\Models\PhoneCountry;
use Illuminate\Database\Seeder;

class PhoneCountrySeeder extends Seeder
{
    public function run(): void
    {
        $rows = require database_path('seeders/data/phone_countries_list.php');

        foreach ($rows as $row) {
            PhoneCountry::updateOrCreate(
                ['iso_code' => $row['iso_code']],
                [
                    'name' => $row['name'],
                    'dial_code' => $row['dial_code'],
                    'flag_emoji' => $row['flag_emoji'],
                    'sort_order' => $row['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
