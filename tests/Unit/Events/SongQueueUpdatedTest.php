<?php

namespace Tests\Unit\Events;

use App\Events\SongQueueUpdated;
use App\Song;
use Tests\TestCase;

class SongQueueUpdatedTest extends TestCase
{
    /** @test */
    public function it_orders_song_queue()
    {
        $songs = factory(Song::class, 3)->create();

        $queue = [
            $songs->find(2)->external_id,
            $songs->find(1)->external_id,
            $songs->find(3)->external_id
        ];

        $event = new SongQueueUpdated($queue);

        $this->assertEquals(array_reverse($queue), array_pluck($event->queue, 'external_id'));
    }
}
