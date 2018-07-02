<?php

namespace App\PlayerStateMachine;

use App\Room;

class PlayerMachine
{
    protected $context;

    /**
     * @var string
     */
    private $deviceId;

    /**
     * @var string
     */
    private $playlistId;

    /**
     * @var Room
     */
    private $room;

    /**
     * PlayerMachine constructor.
     * @param Room $room
     */
    public function __construct(Room $room)
    {
        $this->deviceId = $room->getOriginal('deviceId');
        $this->playlistId = $room->playlistId;
        $this->room = $room;
        $this->context = new PlayerMachineContext();
        $this->context->setState(app(\Session::get('player') ?? IdleState::class));
    }

    /**
     * @param array $songs
     * @return bool
     */
    public function play(array $songs)
    {
        return $this->context->state()->play($this, $songs);
    }

    public function playSong(string $song)
    {
        return $this->context->state()->playSong($this, $song);
    }

    public function pause()
    {
        $this->context->state()->pause($this);
    }

    public function resume()
    {
        $this->context->state()->resume($this);
    }

    public function next()
    {
        $this->context->state()->next($this);
    }

    public function currentState()
    {
        return $this->context->state();
    }

    public function playlistId()
    {
        return $this->playlistId;
    }

    public function deviceId()
    {
        return $this->deviceId;
    }

    public function room()
    {
        return $this->room;
    }

    public function context()
    {
        return $this->context;
    }
}
