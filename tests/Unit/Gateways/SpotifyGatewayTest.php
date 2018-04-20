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

        $playlistId = $this->spotify->createPlaylist('test123', \Auth::user()->spotify_id);

        $this->assertTrue($this->spotify->startPlaylist('f5384d627798e22e5e592a0ab566048b59c57511', $playlistId));
    }

    /** @test */
    public function start_song()
    {
        $this->insertCassette('start_song');

        $this->spotify->startSong('82c86b09fbd6826211f9223a3480f455c65ea17b', ['spotify:track:2Tr5z4vI1RT1EJT6myECjU']);

        $this->assertEquals('spotify:track:2Tr5z4vI1RT1EJT6myECjU', data_get($this->spotify->currentlyPlayingSong(), 'item.uri'));
    }

    /** @test */
    public function pause_player()
    {
        $this->insertCassette('pause_player');

        $success = $this->spotify->pause('82c86b09fbd6826211f9223a3480f455c65ea17b');

        $this->assertTrue($success);
        $this->assertFalse(data_get($this->spotify->getMyCurrentPlaybackInfo(), 'is_playing'));
    }

    /** @test */
    public function resume_player()
    {
        $this->insertCassette('resume_player');

        $success = $this->spotify->resumeSong('82c86b09fbd6826211f9223a3480f455c65ea17b');

        $this->assertTrue($success);
        $this->assertTrue(data_get($this->spotify->getMyCurrentPlaybackInfo(), 'is_playing'));
    }

    /** @test */
    public function skip_track()
    {
        $this->insertCassette('skip_track');

        $playlistId = $this->spotify->createPlaylist('test123', \Auth::user()->spotify_id);

        $this->spotify->addSong($playlistId, '60SJRvzXJnVeVfS4RiH14u');
        $this->spotify->addSong($playlistId, '2Tr5z4vI1RT1EJT6myECjU');

        $this->spotify->startPlaylist('82c86b09fbd6826211f9223a3480f455c65ea17b', $playlistId);

        $this->assertEquals('60SJRvzXJnVeVfS4RiH14u', data_get($this->spotify->currentlyPlayingSong(), 'item.id'));

        $this->insertCassette('skip_track2');

        $this->spotify->next('82c86b09fbd6826211f9223a3480f455c65ea17b');

        $this->assertEquals('2Tr5z4vI1RT1EJT6myECjU', data_get($this->spotify->currentlyPlayingSong(), 'item.id'));
    }

    public function getGateway()
    {
        return new SpotifyGateway();
    }
}
