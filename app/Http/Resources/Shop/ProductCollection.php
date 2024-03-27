<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public static $wrap = 'products';

    private array $pagination = [];

    public function __construct($resource)
    {
        $this->pagination = [
            'links' => [
                "prev" => $resource->previousPageUrl(),
                "next" => $resource->nextPageUrl(),
            ],
            'meta' => [
                'per_page' => $resource->perPage(),
                'current_page' => $resource->currentPage(),
                'total_pages' => $resource->lastPage()
            ],
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ProductResource::collection($this->collection);
    }

    public function with($request)
    {
        return $this->pagination;
    }
}
