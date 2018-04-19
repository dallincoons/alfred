<?php

namespace App\Http\Controllers;

use App\Gateways\CrawlerInterface;
use App\Gateways\GoutteCrawler;
use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\Spotify;
use App\SpotifyUser;
use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SpotifyController extends Controller
{
    /**
     * @var SpotifyGatewayInterface
     */
    private $spotify;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->spotify = app(SpotifyGatewayInterface::class);

            return $next($request);
        });
    }

    public function connect()
    {
        $scopes = explode(',', 'user-read-private,user-read-birthdate,streaming,user-read-playback-state,user-modify-playback-state,playlist-read-private,playlist-read-collaborative,playlist-modify-public,playlist-modify-private,streaming,user-library-read,user-library-modify,user-read-currently-playing,user-read-recently-played');

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
            $user->name,
            $user->token,
            $user->refreshToken,
            (string) array_get($user->user, 'uri')
        ));

        return redirect('/');
    }

    public function songs(Request $request)
    {
        $result = $this->spotify->search($request->q);

        return response()->json($result);
    }
}
