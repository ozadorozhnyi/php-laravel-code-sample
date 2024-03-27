<?php

namespace App\Http\Resources\ProtectedObject;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    /**
     * @var bool
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
            'hub_id' => $this->hub_id,
            'manufacturer' => $this->manufacturer,
            'created_at' => $this->created_at,
        ];
    }
}
