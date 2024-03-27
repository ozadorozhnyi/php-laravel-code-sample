<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product as ShopProduct;
use Illuminate\Http\Response;
use App\Http\Resources\Shop\{
    ProductCollection as ShopProductCollection,
    ProductResource as ShopProductResource
};

use App\Http\Requests\Shop\{
    ProductWithRelatedRequest,
    ProductsWithPaginationRequest
};

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ProductsWithPaginationRequest $request
     * @return ShopProductCollection
     */
    public function index(ProductsWithPaginationRequest $request)
    {
        $validated = $request->validated();

        $products = ShopProduct::withLocale(language()->code)
            ->with('manufacturer', 'photo')
            ->withCategory()
            ->paginate($validated['size']);

        return (new ShopProductCollection(
            $products->appends([
                'size' => $validated['size'],
                'lang' => language()->code,
            ])
        ))->additional(['meta' => [
            'total' => ShopProduct::count()
        ]]);
    }

    /**
     * Display the specified resource.
     *
     * @param ProductWithRelatedRequest $request
     * @return ShopProductResource
     */
    public function show(ProductWithRelatedRequest $request)
    {
        $validated = $request->validated();

        $product = ShopProduct::withLocale(language()->code)
            ->with('manufacturer', 'photo')
            ->withCategory();

        if ($validated['related_qty']) {
            $product->withRelatedProducts(
                $validated['related_qty']
            );
        }

        return new ShopProductResource(
            $product->find($validated['product_id'])
        );
    }

    /**
     * Get the number resource items.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function total()
    {
        return response()->json([
            'success' => true,
            'total' => ShopProduct::count(),
        ], Response::HTTP_OK);
    }
}
