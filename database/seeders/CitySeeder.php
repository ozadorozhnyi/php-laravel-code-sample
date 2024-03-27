<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    protected $cities_service_url = 'https://nyc-brooklyn.ru/goroda-ukrainy-na-angliyskom/';

    /**
     * run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $res = file_get_contents($this->cities_service_url);

        $s = explode("\n",
            preg_replace(["|.*x-alert-block.*?</div>(.*?)</div>.*|s","|<span.*?</span>|","{<br />|<p>|</p>}"],["$1","",""], $res)
        );

        foreach($s as $key => $item) {
            $string = explode("|", $s[$key]);
            if(!$item || empty($string[1])) {
                continue;
            }

            $en_ru = explode("->", $string[0]);
            $string = explode("->", $string[1]);

            $s[$key] = [];

            $string[1] = empty($string[1]) ? $en_ru[1] : $string[1];
            if(empty($string[1]) || empty($string[0]) || strpos($string[0], ',') === false || strpos($string[1], ',') === false) {
                continue;
            }

            $ua = explode(",", $string[0]);
            $ru = explode(",", $en_ru[0]);

            $region_locales = [
                'ua' => trim($ua[1]),
                'ru' => trim($ru[1]),
            ];

            $city_locales = [
                'ua' => trim($ua[0]),
                'ru' => trim($ru[0])
            ];

            if($region_locales['ru']) {
                $region = \App\Models\Localizations\RegionLocalization::query()
                    ->where('locale', 'ru')
                    ->where('name', $region_locales['ru'])
                    ->first();

                if(!$region) {
                    $region = \App\Models\Region::create([]);
                    $region_id = $region->id;
                    foreach($region_locales as $k => $i) {
                        $region->locales()->create(['locale' => $k, 'name' => $i]);
                    }
                } else {
                    $region_id = $region->region_id;
                }

                $city = \App\Models\City::query()
                    ->where('code', 'ru')
                    ->first();

                if(!$city) {
                    try {
                        $city = \App\Models\City::updateOrCreate(['region_id' => $region_id, 'code' => $city_locales['ru']]);
                    } catch (\Exception $e) {
                        $city = \App\Models\City::updateOrCreate(['region_id' => $region_id, 'code' => $city_locales['ru'] . rand(10,99)]);
                    }
                    /**
                     * @var \App\Models\City $city
                     */
                    if($city->wasRecentlyCreated)
                    {
                        foreach ($city_locales as $k => $i)
                        {
                            $city->locales()->create([
                                'locale' => $k,
                                'name' => $i
                            ]);
                        }
                    }
                }
            }
        }


        $this->addedManually();
    }

    private function addedManually()
    {
        // Kiev Region
        $region = \App\Models\Region::create([]);
        $region->locales()->createMany([
            ['name' => 'Киев', 'locale' => 'ru'],
            ['name' => 'Київ', 'locale' => 'ua']
        ]);

        // Kiev City
        $city = \App\Models\City::create(['region_id' => $region->id, 'code' => 'Киев']);
        $city->locales()->createMany([
            ['name' => 'Киев', 'locale' => 'ru'],
            ['name' => 'Київ', 'locale' => 'ua']
        ]);
    }
}
