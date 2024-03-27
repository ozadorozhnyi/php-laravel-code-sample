<?php

namespace App\Http\Resources\ProtectedObject;

use Illuminate\Http\Resources\Json\JsonResource;

class ProtectedObjectResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $geo = $this->getRandomGeo();

        return [
            'id' => $this->id,
            'type' => [
                'id' => $this->type->id,
                'name' => $this->type->locale->name,
            ],
            'status' => [
                'code' => $this->status->code,
                'color' => $this->status->color,
                'slug' => $this->status->slug,
                'name' => $this->status->locale->name,
                'changed_at' => $this->status->created_at->toIso8601ZuluString(),
            ],
            'address' => [
                'region' => $this->region,
                'city' => $this->city,
                'street' => $this->street,
            ],
            'geo' => [
                'lat' => $geo['lat'],
                'lon' => $geo['lon']
            ],
        ];
    }

    private function getRandomGeo()
    {
        $geo = [
            [
                'lat' => 50.46354852888011,
                'lon' => 30.449497528643978
            ], [
                'lat' => 50.463999004489395,
                'lon' => 30.61551355381088
            ], [
                'lat' => 50.38867575193185,
                'lon' => 30.493743774447548
            ],
        ];

        return $geo[\rand(0, count($geo)-1)];
    }
}
