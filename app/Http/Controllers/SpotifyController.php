<?php

namespace App\Http\Controllers;

use App\Gateways\CrawlerInterface;
use App\Gateways\GoutteCrawler;
use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\SpotifyUser;
use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SpotifyController
{
    /**
     * @var SpotifyGatewayInterface
     */
    private $spotify;

    public function __construct()
    {
        $this->spotify = app(SpotifyGatewayInterface::class);
    }

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

        $this->spotify->login(new SpotifyUser(
            $user->id,
            $user->name,
            $user->token,
            $user->refreshToken
        ));

        return redirect('/');
    }

    public function songs(Request $request)
    {
        $result = $this->spotify->search($request->q);

        return response()->json($result);
    }
}
