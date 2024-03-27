<?php

namespace Database\Seeders;

use App\Models\ProtectedObjectType;
use Illuminate\Database\Seeder;

class ProtectedObjectTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('seed.protected_object_types') as $item) {
            $object_type = new ProtectedObjectType();

            $object_type->icon = $item['icon'];
            $object_type->icon_ios = $item['icon_ios'];

            $object_type->save();

            foreach ($item['locales'] as $k => $i) {
                $object_type->locales()->create([
                    'locale' => $k,
                    'name' => $i
                ]);
            }
        }
    }
}
