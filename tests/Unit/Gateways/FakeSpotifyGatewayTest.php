<?php

use App\Gateways\SpotifyGatewayInterface;
use App\SpotifyUser;
use Tests\Fakes\FakeSpotifyGateway;
use Tests\Unit\Gateways\SpotifyGatewayTest;

class FakeSpotifyGatewayTest extends SpotifyGatewayTest
{
    /** @test */
    public function add_song_to_playlist()
    {
        $gateway = $this->getGateway();

        $playlistId = $gateway->createPlaylist('test123', \Auth::user()->spotify_id);

        $gateway->addSong($playlistId, '60SJRvzXJnVeVfS4RiH14u');

        $this->assertContains('60SJRvzXJnVeVfS4RiH14u', $gateway->playlists[$playlistId]->songs);
    }

    /** @test */
    public function it_searches_for_songs()
    {
        $spotifyUser = new SpotifyUser(123456789, 'Paul M', '1111', '22222', 'spotify:1234');
        $this->getGateway()->login($spotifyUser);

        $result = $this->getGateway()->search('summertime teenage bottlerocket');

        $image = data_get($result, 'tracks.items.0.album.images.0');

        $this->assertTrue(is_string(data_get($result, 'tracks.items.0.id')));
        $this->assertTrue(is_string($image->url));
    }

    public function getGateway(): SpotifyGatewayInterface
    {
        return new FakeSpotifyGateway();
    }
}
