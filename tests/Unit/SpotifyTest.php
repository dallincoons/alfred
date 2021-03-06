<?php

namespace Tests\Feature;

use App\GuestUser;
use App\Spotify;
use App\SpotifyUser;
use App\User;
use Tests\Fakes\SpotifyUserFaker;
use Tests\TestCase;

class SpotifyTest extends TestCase
{
    /**
     * @test
     *
     * @vcr storage/test_user_can_login_from_spotify_auth
     */
    public function test_user_can_login_from_spotify_auth()
    {
        User::truncate();

        $spotifyUser = SpotifyUserFaker::any();

        $this->assertEquals(0, User::count());

        Spotify::login($spotifyUser);

        $this->assertEquals(2, User::count());
        $this->assertEquals(User::first()->id, \Auth::user()->id);
        $this->assertTrue(User::find(2)->hasParent());

        $user = \Auth::user();

        $this->assertEquals('Paul M', $user->name);
        $this->assertEquals('11111', $user->access_token);
        $this->assertEquals('spotify:1234', $user->uri);

        $this->spotify->login($spotifyUser);

        $this->assertEquals(2, User::count());
    }

    /** @test */
    public function guest_user_tokens_are_updated_when_user_re_authenticates()
    {
        $spotifyUser = new SpotifyUser(123456789, 'Paul M', '11111', '22222', 'spotify:1234', 'some_url');

        Spotify::login($spotifyUser);

        $user = \Auth::user();

        $this->assertEquals('11111', $user->access_token);
        $this->assertEquals('11111', $user->guestUser->access_token);

        $spotifyUser = new SpotifyUser(123456789, 'Paul M', '33333', '22222', 'spotify:1234', 'some_url');

        Spotify::login($spotifyUser);

        $user = \Auth::user();

        $this->assertEquals('33333', $user->access_token);
        $this->assertEquals('33333', $user->guestUser->access_token);
    }
}
