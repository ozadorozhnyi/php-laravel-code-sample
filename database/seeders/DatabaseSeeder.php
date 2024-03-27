<?php

namespace Database\Seeders;

use Database\Seeders\Shop\CategorySeeder;
use Database\Seeders\Shop\ManufacturerSeeder;
use Database\Seeders\Shop\ShopSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LanguageSeeder::class);
        $this->call(ProtectedObjectTypesSeeder::class);
        $this->call(UserTypesSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(PartnerSeeder::class);
        $this->call(ShopSeeder::class);
        $this->call(ProtectedObjectStatusesSeeder::class);
        $this->call(AlarmSeeder::class);
    }
}
