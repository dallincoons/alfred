<?php

namespace Tests\Fakes;

use App\SpotifyUser;

class SpotifyUserFaker
{
    public static function any()
    {
        return new SpotifyUser(123456789, 'Paul M', '11111', '22222', 'spotify:1234', 'some_url');
    }
}
