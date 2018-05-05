<?php

namespace App\Events;

use App\Song;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SongQueueUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $queue;

    /**
     * Create a new event instance.
     *
     * @param array $songQueue
     */
    public function __construct(array $songQueue)
    {
        $songs = Song::whereIn('external_id', $songQueue)->get();

        $orderedQueue = [];

        foreach(array_reverse($songQueue) as $songInQueue) {
            $orderedQueue[] = ($songs->where('external_id', $songInQueue)->first());
        }

        $this->queue = $orderedQueue;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('song-queue');
    }
}
