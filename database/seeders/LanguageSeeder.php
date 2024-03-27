<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            ['code' => 'ua', 'name' => 'Ukrainian', 'local_name' => 'Українська'],
            ['code' => 'ru', 'name' => 'Russian', 'local_name' => 'Русский'],
        ]);
    }
}
