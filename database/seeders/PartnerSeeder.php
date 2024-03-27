<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class PartnerSeeder extends Seeder
{
    /**
     * @var Collection
     */
    private Collection $partnersLocalizations;

    /**
     * @var array|\string[][]
     */
    private array $citiesSpecified = [];

    /**
     * @var EloquentCollection[]
     */
    private $partnerCities;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->cities = config('seed.partner.cities');
        $this->partners_localizations = $this->getHardcodedLocalizations();

        $partners_count = $this->partners_localizations->count();

        Partner::factory()->count($partners_count)->create()->each(
            function($partner) {

                $basic_partner_id = config('seed.partner.basic_partner_id');

                if ($basic_partner_id === $partner->id) {
                    $partner->is_basic = true;
                    $partner->save();
                }

                $localizations = $this->partners_localizations->shift();
                foreach ($localizations as $locale => $name) {
                    $partner->locales()->create(['name' => $name, 'locale' => $locale]);
                }

                $this->partnerCities = $this->getSpecifiedCities(
                    array_shift($this->cities)
                );

                $locations = collect();
                foreach ($this->partnerCities as $city) {
                    $locations->push(
                        $partner->locations()->create([
                            'partner_id' => $partner->id,
                            'city_id' => $city->id,
                        ])
                    );
                }

                if ($locations->count()) {
                    $streetsLocalizations = $this->getHardcodedStreets();
                    foreach ($locations as $location) {
                        $streetLocales = $streetsLocalizations->shift();
                        foreach ($streetLocales as $locale => $name) {
                            $location->locales()->create([
                                'street' => $name,
                                'locale' => $locale,
                            ]);
                        }
                    }
                }
            }
        );
    }

    /**
     * @return Collection
     */
    private function getHardcodedLocalizations():Collection
    {
        return collect(
            config('seed.partner.localizations')
        );
    }

    /**
     * @return Collection
     */
    private function getHardcodedStreets():Collection
    {
        return collect(
            config('seed.partner.streets')
        );
    }

    /**
     * @param array $cities
     * @return EloquentCollection[]
     */
    private function getSpecifiedCities(array $cities):EloquentCollection
    {
        return \App\Models\City::whereIn("code", $cities)->get();
    }
}
