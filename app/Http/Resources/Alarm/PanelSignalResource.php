<?php

namespace App\Http\Resources\Alarm;

use Illuminate\Http\Resources\Json\JsonResource;

class PanelSignalResource extends JsonResource
{
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
            'attributes' => [
                'object_id' => $this->object_id,
                'event_type' => $this->event_type,
                'event_code' => $this->event_code,
                'created_at' => $this->created_at->toIso8601ZuluString(),
            ],
        ];
    }
}
