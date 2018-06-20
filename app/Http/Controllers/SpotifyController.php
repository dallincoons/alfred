<?php

namespace App\Http\Controllers;

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
        $scopes = explode(',', env('REQUIRED_SCOPES'));

        return Socialite::with('spotify')
            ->setScopes($scopes)
            ->stateless()
            ->redirect();
    }

    public function callback(Request $request)
    {
        $user = Socialite::driver('spotify')->stateless()->user();

        if ($user->user['product'] !== 'premium') {
            return redirect('/not-premium');
        }

        Spotify::login(new SpotifyUser(
            $user->id,
            $user->name,
            $user->token,
            $user->refreshToken,
            (string) array_get($user->user, 'uri')
        ));

        if (\Session::get('create-room')) {
            /** @var User $user */
            $user = \Auth::user()->hasParent() ? \Auth::user() : \Auth::user();
            $room = $user->createRoom(\Session::get('create-room')['name']);
            \Session::forget('create-room');

            return redirect('/rooms/' . $room->getKey());
        }

        return redirect('/');
    }

    public function songs(Request $request)
    {
        $result = $this->spotify->search($request->q);

        return response()->json($result);
    }
}
