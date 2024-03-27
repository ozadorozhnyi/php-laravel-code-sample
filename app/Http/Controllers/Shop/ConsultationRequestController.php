<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreConsultationRequest;
use Illuminate\Http\Response;

class ConsultationRequestController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreConsultationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreConsultationRequest $request)
    {
        $validated = $request->validated();

        $consultationRequest = user()->consultationRequests()->create([
            'partner_id' => $validated['partner_id'],
            'partner_city_id' => $validated['partner_city_id'],
        ]);
        
        if ($request->exists('comment')) {
            $consultationRequest->comment = $validated['comment'];
            $consultationRequest->save();
        }

        $consultationRequest->products()->create([
            'product_id' => $validated['product_id'],
            'qty' => $validated['count'],
        ]);

        return response()->json([
            'id' => $consultationRequest->id,
            'success' => true,
        ], Response::HTTP_CREATED);
    }
}
