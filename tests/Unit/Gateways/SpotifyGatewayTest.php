<?php

namespace Tests\Unit\Gateways;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\GuestUser;
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
        GuestUser::truncate();

        $spotifyUser = new SpotifyUser(123456789, 'Paul M', '11111', '22222', 'spotify:1234');

        $this->assertEquals(0, User::count());

        $this->spotify->login($spotifyUser);

        $this->assertEquals(1, User::count());
        $this->assertEquals(1, GuestUser::count());
        $this->assertEquals(User::first()->id, \Auth::user()->id);

        $user = \Auth::user();

        $this->assertEquals('Paul M', $user->name);
        $this->assertEquals('11111', $user->access_token);
        $this->assertEquals('spotify:1234', $user->uri);

        $this->spotify->login($spotifyUser);

        $this->assertEquals(1, User::count());
    }

//    /** @test */
//    public function it_searches_for_songs()
//    {
//        $spotifyUser = new SpotifyUser(123456789, 'Paul M', env('TEST_SPOTIFY_KEY'), '22222');
//        $this->spotify->login($spotifyUser);
//
//        $result = $this->spotify->search('summertime teenage bottlerocket');
//
//        $image = data_get($result, 'tracks.items.0.album.images.0');
//
//        $this->assertTrue(is_string(data_get($result, 'tracks.items.0.id')));
//        $this->assertTrue(is_string($image->url));
//    }
//
//    /** @test */
//    public function add_song_to_playlist()
//    {
//        $playlistId = $this->spotify->createPlaylist('test123', \Auth::user()->spotify_id);
//
//        $this->spotify->addSong($playlistId, '60SJRvzXJnVeVfS4RiH14u');
//
//        $this->assertEquals('60SJRvzXJnVeVfS4RiH14u', data_get($this->spotify->getPlaylistTracks($playlistId), 'items.0.track.id'));
//    }

    public function getGateway()
    {
        return new SpotifyGateway();
    }
}
