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

    public function getGateway()
    {
        return new SpotifyGateway();
    }
}
