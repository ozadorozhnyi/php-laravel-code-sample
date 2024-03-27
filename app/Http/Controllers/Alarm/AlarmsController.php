<?php

namespace App\Http\Controllers\Alarm;

use App\Classes\Alarm\AlarmDirector;
use App\Http\Controllers\Controller;
use App\Http\Requests\Alarm\StoreFromTheMobileAppRequest;
use App\Http\Resources\Alarm\AlarmSimplifiedResource;
use \Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Exception;

class AlarmsController extends Controller
{
    /**
     * Display the active alarm resource.
     *
     * @return AlarmSimplifiedResource|\Illuminate\Http\Response
     */
    public function active()
    {
        $active_alarm = user()->alarms()->withPanelSignalCode()->latest()->active()->first();

        if (!$active_alarm) {
            return response()->noContent(
                Response::HTTP_OK
            );
        }

        /**
         * Get a single panel signal code,
         * related to the alarm (for automatic alarms only).
         */
        $active_alarm->panel_signal = $active_alarm->panelSignals->first();
        unset($active_alarm->panelSignals);

        return new AlarmSimplifiedResource($active_alarm);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFromTheMobileAppRequest $request
     * @return AlarmSimplifiedResource
     */
    public function store(StoreFromTheMobileAppRequest $request)
    {
        return new AlarmSimplifiedResource(
            (new AlarmDirector())->createFromTheMobileApp(
                Arr::get($request->validated(), 'protected_object_id'),
            )
        );
    }

    // ...Cancel alarm by user itself.
    public function cancel(Request $request)
    {
        $token = $request->input('token');

        try {
            (new AlarmDirector())->cancel($token);
        } catch (Exception $e) {
            return response()->json([
                'title' => "Ошибка!",
                'message' => $e->getMessage(),
            ], Response::HTTP_FORBIDDEN);
        }

        return response()
            ->json(['success' => true])
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    // ...
    public function review(Request $request)
    {
        dd($request->all());
    }
}
