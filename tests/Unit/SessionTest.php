<?php

namespace Tests\Unit;

use App\Room;
use App\Session;
use App\User;
use Tests\TestCase;

class SessionTest extends TestCase
{
    /** @test */
    public function it_gets_session_playlist_from_specific_user()
    {
        $user = factory(User::class)->create();
        $room = factory(Room::class)->create();

        $this->be($user);

        \Session::put(['playlist:' . $room->playlistId => ['test', 'test2']]);

        \Session::save();

        $this->assertEquals(['test', 'test2'], Session::getUserQueue($user->getKey(), $room->playlistId));
    }
}
