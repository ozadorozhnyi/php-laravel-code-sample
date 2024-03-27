<?php

namespace App\Http\Resources\Alarm;

use Illuminate\Http\Resources\Json\JsonResource;

class AlarmResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
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
            'type' => 'alarms',
            'attributes' => [
                'token' => $this->token,
                'created_at' => $this->created_at->toIso8601ZuluString(),
                'updated_at' => $this->updated_at->toIso8601ZuluString(),
            ],
            'relationships' => [
                'user' => [
                    'id' => $this->user->id,
                    'first_name' => $this->user->first_name,
                    'last_name' => $this->user->last_name,
                    'email' => $this->user->email,
                    'phone' => $this->user->phone,
                ],
                'protected_object' => [
                    'id' => $this->protectedObject->id,
                    'type_id' => $this->protectedObject->type_id,
                    'city' => $this->protectedObject->city,
                    'region' => $this->protectedObject->region,
                    'street' => $this->protectedObject->street,
                    'geo' => [
                        'lat' => $this->protectedObject->latitude,
                        'lon' => $this->protectedObject->longitude
                    ],
                ],
                'type' => [
                    'id' => $this->type->id,
                    'slug' => $this->type->slug,
                    'name' => $this->type->locale->name,
                    'description' => $this->type->locale->description,
                ],
                'status' => [
                    'id' => $this->status->id,
                    'code' => $this->status->code,
                    'slug' => $this->status->slug,
                    'name' => $this->status->locale->name,
                    'description' => $this->status->locale->description,
                ],
                'panel_signal' => $this->when(isset($this->panel_signal), function(){
                    return [
                        'id' => $this->panel_signal->id,
                        'code' => $this->panel_signal->code,
                        'slug' => $this->panel_signal->slug,
                        'name' => $this->panel_signal->locale->name,
                        'description' => $this->panel_signal->locale->description,
                        'created_at' => $this->panel_signal->created_at->toIso8601ZuluString(),
                        'updated_at' => $this->panel_signal->updated_at->toIso8601ZuluString(),
                    ];
                }),
            ],
        ];
    }
}
