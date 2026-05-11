<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            PhoneCountrySeeder::class,
            AdminSeeder::class,
            SliderSeeder::class,
            ServiceSeeder::class,
            TestimonialSeeder::class,
            MenuSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
        ]);
    }
}
