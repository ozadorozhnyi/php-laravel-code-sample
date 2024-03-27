<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PartnerCollection extends ResourceCollection
{
    public static $wrap = false;
    private $rendered = [];
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach ($this->combined() as $item) {
            $this->rendered[] = $item;
        }

        return [
            'partners' => $this->rendered
        ];
    }

    /**
     * @return array
     */
    private function combined():array
    {
        $regions = [];
        $combined = [];

        foreach ($this->collection as $item) {

            $regions[$item->partner_id][] = [
                "region_id" => $item->city->region_id,
                "region_name" => $item->city->region()->withLocale(language()->code)->first()->locale->name,
                "city_id" => $item->city->id,
                "city_name" => $item->city->locale->name,
                "street" => $item->locale->street,
            ];

            $combined[$item->partner_id] = [
                "id" => $item->partner_id,
                "phone" => $item->partner->phone,
                "name" => $item->partner->locale->name,
                "default" => $item->partner->is_basic,

                "regions" => $regions[$item->partner_id],
            ];
        }

        return $combined;
    }
}
