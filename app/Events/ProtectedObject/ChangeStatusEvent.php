<?php

namespace App\Events\ProtectedObject;

use App\Models\ProtectedObject;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class ChangeStatusEvent
 * @package App\Events\ProtectedObject
 *
 */
class ChangeStatusEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var ProtectedObject
     */
    public ProtectedObject $protectedObject;

    /**
     * @var string
     */
    public string $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ProtectedObject $protectedObject, string $status)
    {
        $this->protectedObject = $protectedObject;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
