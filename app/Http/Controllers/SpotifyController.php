<?php

namespace App\Http\Controllers;

use App\Gateways\ExternalSong;
use App\Gateways\PlaylistExternalSong;
use App\Gateways\SongSearchGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\Http\Requests\QueueSongRequest;
use App\Repositories\GuestNameRepository;
use App\Room;
use App\Song;
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

        app(GuestNameRepository::class)->setUserName($user->name);

        if (\Session::get('create-room')) {
            /** @var User $user */
            $user = \Auth::user();
            $room = $user->createRoom(\Session::get('create-room')['name']);
            \Session::forget('create-room');

            \Cookie::queue('room_code', $room->code);

            return redirect('/rooms/' . $room->getKey());
        }

        return redirect('/');
    }

    public function search(Request $request)
    {
        $searchGateway = app(SongSearchGateway::class);

        $songs = $searchGateway->searchSongs($request->q, $request->room);

        return response()->json($songs->toArray());
    }

    public function currentlyPlayingSong()
    {
        $result = $this->spotify->currentlyPlayingSong();

        $songInfo = Song::where('external_id', $result->id())->first();

        $result->setContributorName(optional($songInfo)->added_by ?? 'Guest');

        return response()->json($result->toArray());
    }

    public function addSongToQueue(QueueSongRequest $request)
    {
        try {
            $success = $this->spotify->queueSong($request->song_uri);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }

        if (!$success) {
            return response('song could not be added', 500);
        }

        return response('song added to queue', 200);
    }
}
