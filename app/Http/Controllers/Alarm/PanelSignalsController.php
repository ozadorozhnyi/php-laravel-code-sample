<?php

namespace App\Http\Controllers\Alarm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Alarm\PanelSignalStoreRequest;
use Illuminate\Support\Facades\Log;
use App\Classes\Alarm\AlarmDirector;
use App\Classes\Alarm\Exceptions\MismatchedObjectIdException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;

class PanelSignalsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param PanelSignalStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PanelSignalStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            (new AlarmDirector())->createFromPanelSignal(
                Arr::get($validated, 'ID'),
                Arr::get($validated, 'EventID'),
                Arr::get($validated, 'EventCode'),
            );
        } catch (MismatchedObjectIdException $e) {
            Log::critical($e->getMessage());
        }

        return response()->noContent(
            Response::HTTP_CREATED
        );
    }
}
