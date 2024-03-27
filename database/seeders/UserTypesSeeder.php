<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    protected $types = [
        [
            'locales' => [
            'ua' => 'Кліент',
            'ru' => 'Клиент'
        ]],
        [
            'locales' => [
            'ua' => 'Екіпаж',
            'ru' => 'Экипаж'
        ]]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->types as $item) {
            $user_type = new UserType();
            $user_type->save();
            foreach ($item['locales'] as $k => $i) {
                $user_type->locales()->create([
                    'locale' => $k,
                    'name' => $i
                ]);
            }
        }
    }
}
