<?php

namespace App;

use App\Events\SongQueueStarted;
use App\Gateways\ExternalSong;
use App\Gateways\SpotifyGatewayInterface;
use App\PlayerStateMachine\PlayerMachine;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name', 'playlistId', 'deviceId'
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

    /**
     * @var PlayerMachine
     */
    private $machine;

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

    public function addSong(ExternalSong $song)
    {
        $songId = $song->getId();

        $song = Song::firstOrCreate([
            'external_id' => $songId,
            'title' => $song->getTitle(),
            'artist_title' => $song->getArtistTitle()
        ]);

        $this->songs()->attach($song);

        SongQueue::addSong($this->playlistId, $songId);

        $this->gateway->addSong($this->playlistId, $songId);
    }

    public function playerState()
    {
        if (!$this->state) {
            $this->state = new PlayerMachine($this);
        }
        return $this->state;
    }

    public function play()
    {
        return $this->playerState()->play($this->songs()->pluck('external_id')->all());
    }

    public function pause(string $deviceId)
    {
        return $this->gateway->pause($deviceId);
    }

    public function resume(string $deviceId)
    {
        return $this->gateway->resumeSong($deviceId);
    }

    public function next()
    {
        $currentSong = SongQueue::next($this->playlistId);

        if(!$currentSong) {
            $currentSong = $this->reset();
        }

        SongQueueStarted::dispatch(Song::where('external_id', $currentSong)->first());

        return $this->gateway->startSong($this->deviceId, 'spotify:track:' . $currentSong);
    }

    public function reset(): string
    {
        $songIds = $this->songs()->pluck('external_id')->all();
        return SongQueue::play($this->playlistId, $songIds);
    }
}
