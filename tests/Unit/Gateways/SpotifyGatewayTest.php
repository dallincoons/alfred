<?php

namespace Tests\Unit\Gateways;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\Spotify;
use App\SpotifyUser;
use Tests\TestCase;
use Tests\Traits\UsesVcr;

class SpotifyGatewayTest extends TestCase
{
    use UsesVcr;

    /**
     * @var SpotifyGatewayInterface
     */
    protected $spotify;

    protected function setUp()
    {
        parent::setUp();

        $this->setCassettePath('tests/Unit/Gateways/fixtures');

        $this->spotify = $this->getGateway();
    }

    public function test()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_searches_for_songs()
    {
        $this->insertCassette('it_searches_for_songs');

        $spotifyUser = new SpotifyUser(123456789, 'Paul M', env('TEST_SPOTIFY_KEY'), '22222', 'spotify:1234');
        Spotify::login($spotifyUser);

        $result = $this->spotify->search('summertime teenage bottlerocket');

        $image = data_get($result, 'tracks.items.0.album.images.0');

        $this->assertTrue(is_string(data_get($result, 'tracks.items.0.id')));
        $this->assertTrue(is_string($image->url));
    }

    /** @test */
    public function add_song_to_playlist()
    {
        $this->insertCassette('add_song_to_playlist');

        $playlistId = $this->spotify->createPlaylist('test123', \Auth::user()->spotify_id);

        $this->spotify->addSong($playlistId, '60SJRvzXJnVeVfS4RiH14u');

        $this->assertEquals('60SJRvzXJnVeVfS4RiH14u', data_get($this->spotify->getPlaylistTracks($playlistId), 'items.0.track.id'));
    }

    /** @test */
    public function transfer_control_to_device()
    {
        $this->insertCassette('transfer_control_to_device');

        $this->assertTrue($this->spotify->changeDevice('f5384d627798e22e5e592a0ab566048b59c57511'));
    }

    /** @test */
    public function start_playlist()
    {
        $this->insertCassette('start_playlist');

        $this->assertTrue($this->spotify->startPlaylist('f5384d627798e22e5e592a0ab566048b59c57511', '1sxZ063FPv9ym5OS5nQIbV'));
    }

    /** @test */
    public function start_song()
    {
        $this->insertCassette('start_song');

        $this->spotify->startSong('82c86b09fbd6826211f9223a3480f455c65ea17b', ['spotify:track:2Tr5z4vI1RT1EJT6myECjU']);

        $this->assertEquals('Desmond Dekker', data_get($this->spotify->currentlyPlayingSong(), 'item.album.artists.0.name'));
    }

    public function getGateway()
    {
        return new SpotifyGateway();
    }
}
