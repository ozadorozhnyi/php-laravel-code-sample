<?php

namespace App\Http\Resources\ProtectedObject;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProtectedObjectCollection extends ResourceCollection
{
    public static $wrap = 'protected_objects';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ObjectResource::collection(
            $this->collection
        );
    }
}
