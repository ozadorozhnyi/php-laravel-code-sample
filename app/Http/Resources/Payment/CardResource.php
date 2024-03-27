<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    const TOKEN_LIFETIME_FORMAT = 'd-m-Y H:i:s';

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
            'type' => $this->type,
            'last4digits' => $this->last4digits,
            'token' => $this->token,
            'token_lifetime' => $this->token_lifetime->format(self::TOKEN_LIFETIME_FORMAT),
            'created_at' => $this->created_at,
        ];
    }
}
