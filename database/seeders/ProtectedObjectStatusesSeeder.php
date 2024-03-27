<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProtectedObjectStatus;

class ProtectedObjectStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = config('seed.protected_object.statuses');

        foreach ($statuses as $item) {
            $protected_object_status = new ProtectedObjectStatus();

            $protected_object_status->slug = $item['slug'];
            $protected_object_status->code = $item['code'];
            $protected_object_status->color = $item['color'];

            $protected_object_status->save();

            foreach ($item['locales'] as $locale => $data) {
                $protected_object_status->locales()->create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'locale' => $locale
                ]);
            }
        }
    }
}
