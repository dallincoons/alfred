<?php

namespace Tests\Unit\Gateways;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\SpotifyUser;
use App\User;
use Tests\TestCase;

class SpotifyGatewayTest extends TestCase
{
    /**
     * @var SpotifyGatewayInterface
     */
    protected $spotify;

    protected function setUp()
    {
        parent::setUp();

        $this->spotify = $this->getGateway();
    }

    /**
     * @test
     *
     * @vcr storage/test_user_can_login_from_spotify_auth
     */
    public function test_user_can_login_from_spotify_auth()
    {
        User::truncate();

        $spotifyUser = new SpotifyUser(123456789, 'Paul M', '11111', '22222');

        $this->assertEquals(0, User::count());

        $this->getGateway()->login($spotifyUser);

        $this->assertEquals(1, User::count());
        $this->assertEquals(User::first()->id, \Auth::user()->id);

        $this->spotify->login($spotifyUser);

        $this->assertEquals(1, User::count());
    }

//    /** @test */
//    public function it_can_create_playlist()
//    {
//        $this->spotify->createPlaylist('test123', \Auth::user()->spotify_id);
//    }

    public function getGateway()
    {
        return new SpotifyGateway();
    }
}
