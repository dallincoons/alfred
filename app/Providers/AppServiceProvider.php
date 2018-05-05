<?php

namespace App\Providers;

use App\Gateways\CrawlerInterface;
use App\Gateways\GoutteCrawler;
use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\PlayerStateMachine\IdleState;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(CrawlerInterface::class, GoutteCrawler::class);
        app()->bind(SpotifyGatewayInterface::class, SpotifyGateway::class);
    }
}
