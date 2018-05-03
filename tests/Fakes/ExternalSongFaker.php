<?php

namespace Tests\Fakes;

use App\Gateways\ExternalSong;

class ExternalSongFaker
{
    public static function withId(string $id)
    {
        return new ExternalSong([
            'id' => $id
        ]);
    }
}
