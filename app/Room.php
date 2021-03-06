<?php

namespace App;

use App\Events\SongAdded;
use App\Gateways\ExternalSong;
use App\Gateways\SpotifyGatewayInterface;
use App\Observers\RoomObserver;
use App\PlayerStateMachine\PlayerMachine;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name', 'playlistId', 'deviceId', 'user_id', 'code'
    ];

    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    /**
     * @var SpotifyGatewayInterface
     */
    private $gateway;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->codeGenerator = app(CodeGenerator::class);
        $this->gateway = app(SpotifyGatewayInterface::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($room) {
            if (!$room->code) {
                $room->code = app(CodeGenerator::class)->encode(Room::count() + 1);
            }
        });
    }

    public function sync()
    {
        $playlistTracks = collect($this->gateway->getPlaylistTracks($this->playlistId));

        $playlistTrackIds = $playlistTracks
            ->map(function(ExternalSong $song) {
                return $song->getId();
            });

        $tracksToAdd = $playlistTrackIds->diff($this->songs->pluck('external_id'))->all();

        $playlistTracks->each(function(ExternalSong $track) use ($tracksToAdd) {
            if (in_array($track->getId(), $tracksToAdd)) {
                $this->createSong($track, 'Spotify');
            }
        });

        $this->songs->filter(function ($song) use ($playlistTrackIds) {
            return !$playlistTrackIds->contains($song->external_id);
        })->each(function($song) {
            $song->delete();
        });

        $this->refresh();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function songs()
    {
        return $this->belongsToMany(Song::class);
    }

    /**
     * @return string
     */
    public function share()
    {
        return $this->codeGenerator->encode(Room::count() + 1);
    }

    /**
     * @param string $roomId
     * @return bool
     */
    public function join(string $roomId)
    {
        if (array_get($this->codeGenerator->decode($roomId), 0) == $this->getKey()) {
            \Auth::login($this->user->guestUser);
            return true;
        }

        return false;
    }

    /**
     * @param string $deviceId
     */
    public function storeDeviceId(string $deviceId)
    {
        $this->update([
            'deviceId' => $deviceId
        ]);
    }

    /**
     * @return string
     */
//    public function getExistingDeviceIdAttribute()
//    {
//        return \Auth::user()->hasParent() ? $this->attributes['deviceId'] : '';
//    }

    public function getQueue()
    {
        return SongQueue::all($this->playlistId);
    }

    /**
     * @param ExternalSong $song
     * @param string $addedByName
     * @return Song
     */
    public function addSong(ExternalSong $song, string $addedByName): Song
    {
        $songId = $song->getId();

        $song = $this->createSong($song, $addedByName);

        SongAdded::dispatch($song);

        $this->gateway->addSong($this->playlistId, $songId);

        return $song;
    }

    /**
     * @param string $songId
     * @return bool
     */
    public function removeSong(string $songId)
    {
        $this->songs()->where('external_id', $songId)->delete();

        return $this->gateway->delete($this->user->spotify_id, $this->playlistId, $songId);
    }

    /**
     * @return string
     */
    public function queueSessionName()
    {
        return 'playlist:' . $this->playlistId;
    }

    /**
     * @return PlayerMachine|mixed
     */
    public function player()
    {
        if (!$this->state) {
            $this->state = new PlayerMachine($this);
        }
        return $this->state;
    }

    /**
     * @return bool
     */
    public function play()
    {
        return $this->player()->play($this->songs()->pluck('external_id')->all());
    }

    public function pause()
    {
        $this->player()->pause();
    }

    public function resume()
    {
        $this->player()->resume();
    }

    public function next()
    {
        $this->player()->next();
    }

    public function previous()
    {
        $this->player()->previous();
    }

    /**
     * @return string
     */
    public function reset(): string
    {
        $songIds = $this->songs()->pluck('external_id')->all();
        return SongQueue::play($this->playlistId, $songIds);
    }

    /**
     * @param ExternalSong $song
     * @param string $addedByName
     * @return Song
     */
    protected function createSong(ExternalSong $song, string $addedByName)
    {
        $song = Song::create([
            'external_id' => $song->getId(),
            'title' => $song->getTitle(),
            'artist_title' => $song->getArtistTitle(),
            'duration' => $song->getDuration(),
            'big_image' => $song->getBigImage(),
            'added_by' => $addedByName,
        ]);
        $this->songs()->attach($song);

        return $song;
    }
}
