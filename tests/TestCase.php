<?php

namespace Tests;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\SpotifyUser;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Fakes\FakeSpotifyGateway;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * @var SpotifyGatewayInterface
     */
    protected $spotify;

    /**
     * @var User
     */
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->app->singleton(SpotifyGatewayInterface::class, FakeSpotifyGateway::class);

        $this->spotify = app(SpotifyGatewayInterface::class);

        $spotifyUser = new SpotifyUser(1213003440, 'Paul M', '11111', '22222');

        $this->spotify->login($spotifyUser);

        $this->user = \Auth::user();
    }
}
