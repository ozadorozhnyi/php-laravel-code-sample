<?php

namespace App\Http\Controllers\Account;

use App\Events\ProtectedObject\ChangeStatusEvent;
use App\Models\PartnerApplication;
use App\Models\ProtectedObjectStatus;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\ProtectedObject\StorePartnerApplicationRequest;

class PartnerApplicationController extends Controller
{
    /**
     * Store a newly created resource in storage..
     *
     * @param StorePartnerApplicationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePartnerApplicationRequest $request)
    {
        $validated = $request->validated();

        $application = new PartnerApplication();

        $application->partner_id = $validated['partner_id'];
        $application->protected_object_id = $validated['protected_object_id'];
        $application->region_id = $validated['region_id'];

        if ($request->exists('comment')) {
            $application->comment = $validated['comment'];
        }

        $application->save();

        ChangeStatusEvent::dispatch(
            $application->protectedObject,
            ProtectedObjectStatus::WAITING_FOR_THE_EQUIPMENT
        );

        return response()->json([
            'id' => $application->id,
            'success' => true,
        ], Response::HTTP_CREATED);
    }
}
