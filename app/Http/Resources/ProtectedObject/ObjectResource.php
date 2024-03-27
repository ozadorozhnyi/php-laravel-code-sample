<?php

namespace App\Http\Resources\ProtectedObject;

use Illuminate\Http\Resources\Json\JsonResource;

class ObjectResource extends JsonResource
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
        return [
            'id' => $this->id,
            'type' => 'protected_objects',
            'attributes' => [
                'city' => $this->city,
                'region' => $this->region,
                'street' => $this->street,
                'house' => $this->house,
                'detached_building' => $this->detached_building,
                'number_of_inputs' => $this->number_of_inputs,
                'apartment' => $this->apartment,
                'floor' => $this->floor,
                'entrance' => $this->entrance,
                'addition' => $this->addition,
                'geo' => [
                    'lat' => $this->latitude,
                    'lon' => $this->longitude
                ],
                'created_at' => $this->created_at->toIso8601ZuluString(),
                'updated_at' => $this->updated_at->toIso8601ZuluString(),
            ],
            'relationships' => [
                'type' => [
                    'id' => $this->type->id,
                    'name' => $this->type->locale->name,
                    'icon' => asset($this->type->icon),
                    'icon_ios' => asset($this->type->icon_ios),
                ],
                'status' => [
                    'code' => $this->status->code,
                    'color' => $this->status->color,
                    'slug' => $this->status->slug,
                    'name' => $this->status->locale->name,
                    'changed_at' => $this->status->created_at->toIso8601ZuluString(),
                ],
                'owner' => [
                    'id' => 1,
                    'first_name' => $this->owner->first_name,
                    'last_name' => $this->owner->last_name,
                    'father_name' => $this->owner->father_name,
                    'passport' => $this->owner->passport,
                    'passport_issued' => $this->owner->passport_issued,
                    'passport_date' => $this->owner->passport_date,
                    'id_tax' => $this->owner->id_tax,
                    'id_passport' => $this->owner->id_passport,
                    'created_at' => $this->owner->created_at->toIso8601ZuluString(),
                    'updated_at' => $this->owner->updated_at->toIso8601ZuluString(),
                ],
            ],
        ];
    }
}
