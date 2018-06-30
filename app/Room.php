<?php

namespace App;

use App\Events\SongAdded;
use App\Events\SongQueueUpdated;
use App\Gateways\ExternalSong;
use App\Gateways\SpotifyGatewayInterface;
use App\PlayerStateMachine\PlayerMachine;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name', 'playlistId', 'deviceId', 'user_id'
    ];

    protected $appends = [
        'existingDeviceId'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class);
    }

    public function share()
    {
        return $this->codeGenerator->encode($this->getKey());
    }

    public function join(string $roomId)
    {
        if (array_get($this->codeGenerator->decode($roomId), 0) == $this->getKey()) {
            \Auth::login($this->user->guestUser);
            return true;
        }

        return false;
    }

    public function storeDeviceId(string $deviceId)
    {
        $this->update([
            'deviceId' => $deviceId
        ]);
    }

    public function getExistingDeviceIdAttribute()
    {
        return \Auth::user()->hasParent() ? $this->attributes['deviceId'] : '';
    }

    public function getQueue()
    {
        return SongQueue::all($this->playlistId);
    }

    /**
     * @param ExternalSong $song
     * @return Song
     */
    public function addSong(ExternalSong $song): Song
    {
        $songId = $song->getId();

        $song = Song::firstOrCreate([
            'external_id' => $songId,
            'title' => $song->getTitle(),
            'artist_title' => $song->getArtistTitle(),
            'duration' => $song->getDuration(),
            'big_image' => $song->getBigImage()
        ]);

        $this->songs()->attach($song);

        SongAdded::dispatch($song);

        $this->gateway->addSong($this->playlistId, $songId);

        return $song;
    }

    public function queueSessionName()
    {
        return 'playlist:' . $this->playlistId;
    }

    public function player()
    {
        if (!$this->state) {
            $this->state = new PlayerMachine($this);
        }
        return $this->state;
    }

    public function play()
    {
        return $this->player()->play($this->songs()->pluck('external_id')->all());
    }

    public function pause()
    {
        return $this->player()->pause();
    }

    public function resume()
    {
        return $this->player()->resume();
    }

    public function next()
    {
        return $this->player()->next();
    }

    public function reset(): string
    {
        $songIds = $this->songs()->pluck('external_id')->all();
        return SongQueue::play($this->playlistId, $songIds);
    }
}
