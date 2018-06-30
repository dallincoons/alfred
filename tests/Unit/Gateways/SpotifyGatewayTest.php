<?php

namespace Tests\Unit\Gateways;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\Spotify;
use App\SpotifyUser;
use Tests\Fakes\SpotifyUserFaker;
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

    /** @test */
    public function it_searches_for_songs()
    {
        $this->insertCassette('it_searches_for_songs');

        $spotifyUser = SpotifyUserFaker::any();
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

        $this->assertTrue($this->spotify->changeDevice('82c86b09fbd6826211f9223a3480f455c65ea17b'));
    }

    /** @test */
    public function start_playlist()
    {
        $this->insertCassette('start_playlist');

        $playlistId = $this->spotify->createPlaylist('test123', \Auth::user()->spotify_id);

        $this->assertTrue($this->spotify->startPlaylist('82c86b09fbd6826211f9223a3480f455c65ea17b', $playlistId));
    }

    public function getGateway()
    {
        return new SpotifyGateway();
    }
}
