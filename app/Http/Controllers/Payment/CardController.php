<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\{DestroyCard, StoreCard, ShowCard};
use App\Http\Resources\Payment\{CardResource, CardCollection};
use Illuminate\Http\Response;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CardCollection
     */
    public function index()
    {
        return new CardCollection(
            user()->paymentCards
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCard $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCard $request)
    {
        $paymentCard = user()->paymentCard()->create(
            $request->validated()
        );

        return response()->json([
            'id' => $paymentCard->id,
            'success' => true,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param ShowCard $request
     * @return CardResource
     */
    public function show(ShowCard $request)
    {
        $validated = $request->validated();

        $card = user()->paymentCard()
            ->where('token', $validated['token'])
            ->first();

        return new CardResource($card);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCard $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyCard $request)
    {
        $validated = $request->validated();

        $deleted = user()->paymentCard()
            ->where('token', $validated['token'])
            ->delete();

        return response()->json([
            'success' => $deleted,
        ], Response::HTTP_OK);
    }
}
