<?php

namespace App\Http\Resources\Alarm;

use Illuminate\Http\Resources\Json\JsonResource;

class AlarmSimplifiedResource extends JsonResource
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
            'token' => $this->token,
            'type' => $this->type->slug,
        ];
    }
}
