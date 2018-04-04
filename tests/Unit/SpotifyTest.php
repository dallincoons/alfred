<?php

namespace Tests\Unit;

use App\Gateways\Spotify;
use App\SpotifyUser;
use App\User;
use Tests\TestCase;

class SpotifyTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_user_can_login_from_spotify_auth()
    {
        $spotifyUser = new SpotifyUser(123456789, 'Paul M');

        $this->assertEquals(0, User::count());

        Spotify::login($spotifyUser);

        $this->assertEquals(1, User::count());
        $this->assertEquals(User::first()->id, \Auth::user()->id);

        Spotify::login($spotifyUser);

        $this->assertEquals(1, User::count());
    }
}
