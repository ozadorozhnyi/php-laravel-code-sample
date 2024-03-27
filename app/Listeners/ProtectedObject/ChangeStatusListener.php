<?php

namespace App\Listeners\ProtectedObject;

use App\Events\ProtectedObject\ChangeStatusEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ProtectedObjectStatus;
use Carbon\Carbon;

class ChangeStatusListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ChangeStatusEvent $event
     * @return void
     */
    public function handle(ChangeStatusEvent $event)
    {
        $protectedObject = $event->protectedObject;

        $status = ProtectedObjectStatus::where(
            'slug', $event->status
        )->first();

        $protectedObject->statuses()->attach($status->id, [
            'created_at' => Carbon::now()
        ]);
    }
}
