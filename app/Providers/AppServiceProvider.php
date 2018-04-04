<?php

namespace App\Providers;

use App\Gateways\CrawlerInterface;
use App\Gateways\GoutteCrawler;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(CrawlerInterface::class, GoutteCrawler::class);
    }
}
