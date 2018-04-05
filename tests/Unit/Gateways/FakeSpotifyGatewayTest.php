<?php

use App\Gateways\SpotifyGatewayInterface;
use Tests\Fakes\FakeSpotifyGateway;
use Tests\Unit\Gateways\SpotifyGatewayTest;

class FakeSpotifyGatewayTest extends SpotifyGatewayTest
{
    public function getGateway(): SpotifyGatewayInterface
    {
        return new FakeSpotifyGateway();
    }
}
