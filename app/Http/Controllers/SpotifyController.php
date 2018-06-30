<?php

namespace App\Http\Controllers;

use App\Gateways\ExternalSong;
use App\Gateways\PlaylistExternalSong;
use App\Gateways\SpotifyGatewayInterface;
use App\Room;
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
            (string) array_get($user->user, 'uri'),
            (string) array_get($user->user, 'images.0.url')
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

    public function search(Request $request)
    {
        $result = $this->spotify->search($request->q);

        $room = Room::findOrFail($request->room);

        $songIds = $room->songs->pluck('external_id')->all();

        $rawSongs = data_get($result, 'tracks.items');

        $songs = collect($rawSongs)
            ->map(function($song) use ($songIds) {
                return new PlaylistExternalSong($song, $songIds);
            });

        return response()->json($songs->toArray());
    }
}
