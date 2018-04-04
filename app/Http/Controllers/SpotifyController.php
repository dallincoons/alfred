<?php

namespace App\Http\Controllers;

use App\Gateways\CrawlerInterface;
use App\Gateways\GoutteCrawler;
use App\Gateways\Spotify;
use App\SpotifyUser;
use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SpotifyController
{
    public function connect()
    {
        $scopes = explode(',', 'playlist-read-private,playlist-read-collaborative,playlist-modify-public,playlist-modify-private,streaming,user-library-read,user-library-modify,user-read-currently-playing,user-read-recently-played');

        return Socialite::with('spotify')
            ->setScopes($scopes)
            ->stateless()
            ->redirect();
    }

    public function callback(Request $request)
    {
        $user = Socialite::driver('spotify')->stateless()->user();

        Spotify::login(new SpotifyUser(
            $user->id,
            $user->name
        ));

        return redirect('/');
    }
}
