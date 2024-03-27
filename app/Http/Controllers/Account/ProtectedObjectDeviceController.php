<?php

namespace App\Http\Controllers\Account;

use App\Events\ProtectedObject\ChangeStatusEvent;
use App\Http\Requests\Account\ProtectedObject\StoreDeviceRequest;
use App\Http\Resources\ProtectedObject\DeviceResource;
use App\Http\Controllers\Controller;
use App\Models\{ProtectedObject, ProtectedObjectStatus};

class ProtectedObjectDeviceController extends Controller
{
    /**
     *
     * Store a newly created resource in storage.
     *
     * @param StoreDeviceRequest $request
     * @return DeviceResource
     */
    public function store(StoreDeviceRequest $request)
    {
        $validated = $request->validated();

        $protectedObject = ProtectedObject::find(
            $validated['protected_object_id']
        );

        $result = $protectedObject->device()->create($validated);

        ChangeStatusEvent::dispatch(
            $protectedObject,
            ProtectedObjectStatus::EQUIPMENT_SUCCESSFULLY_ADDED
        );

        return new DeviceResource($result);
    }
}
