<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'id' => $this->id,
            'category_id' => $this->category->id,
            'category_name' => $this->category->locale->name,
            'manufacturer_id' => $this->manufacturer->id,
            'manufacturer_name' => $this->manufacturer->name,
            'name' => $this->locale->name,
            'description' => $this->locale->description,
            'color' => $this->locale->color,
            'price' => $this->price,
            'sku' => $this->sku,
            'new_arrival' => (bool)$this->IsANewArrival,
            'photo' => $this->photo->getUrl(),
            'related_products' => $this->when(isset($this->relatedProducts), function() {
                return  $this->getRelatedProducts();
            })
        ];
    }

    private function getRelatedProducts()
    {
        $related = [];

        if($this->relatedProducts->count()) {
            foreach ($this->relatedProducts as $product) {
                $related[] = [
                    'id' => $product->id,
                    'category_id' => $product->category->id,
                    'category_name' => $product->category->locale->name,
                    'manufacturer_id' => $product->manufacturer->id,
                    'manufacturer_name' => $product->manufacturer->name,
                    'name' => $product->locale->name,
                    'description' => $product->locale->description,
                    'color' => $product->locale->color,
                    'price' => $product->price,
                    'sku' => $product->sku,
                    'new_arrival' => (bool)$product->IsANewArrival,
                    'photo' => $product->photo->getUrl(),
                ];
            }
        }

        return $related;
    }
}
