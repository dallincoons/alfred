<?php

use App\Room;
use Tests\TestCase;

class RoomTest extends TestCase
{
    /** @test */
    public function it_can_create_room()
    {
        $this->post('/rooms', ['room_name' => 'test123']);

        $this->assertEquals(1, Room::count());
        $this->assertEquals('test123', Room::first()->name);
    }
}
