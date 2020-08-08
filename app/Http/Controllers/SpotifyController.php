<?php

namespace App\Http\Controllers;

use App\Gateways\ExternalSong;
use App\Gateways\PlaylistExternalSong;
use App\Gateways\SpotifyGatewayInterface;
use App\Http\Requests\QueueSongRequest;
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

        $previouslyAddedSongs = Song::query()
            ->select(['external_id', 'added_by', 'room_song.song_id', 'room_song.room_id'])
            ->join('room_song', 'songs.id', '=', 'room_song.song_id')
            ->where('room_song.room_id', '=', $room->getKey())
            ->whereIn('external_id', $songIds)
            ->get()
            ->keyBy('external_id');

        $songs = collect($rawSongs)
            ->map(function($song) use ($songIds, $previouslyAddedSongs) {
                $externalSong = new PlaylistExternalSong($song, $songIds);

                if (isset($previouslyAddedSongs[$externalSong->getId()])) {
                    $externalSong->setAddedBy($previouslyAddedSongs[$externalSong->getId()]->added_by);
                }

                return $externalSong;
            });

        \Log::info($songs->toArray());

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
