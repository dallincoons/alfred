<?php

use App\Gateways\SpotifyGatewayInterface;
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

    public function getGateway(): SpotifyGatewayInterface
    {
        return new FakeSpotifyGateway();
    }
}
