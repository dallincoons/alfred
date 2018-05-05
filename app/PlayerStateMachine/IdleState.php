<?php

namespace App\PlayerStateMachine;

use App\Events\SongQueueStarted;
use App\Gateways\SpotifyGatewayInterface;
use App\Song;
use Illuminate\Support\Facades\Session;

class IdleState implements PlayerMachineState
{
    /**
     * @var SpotifyGatewayInterface
     */
    private $gateway;

    public function __construct(SpotifyGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function play(PlayerMachine $playerMachine, array $songs): bool
    {

        if(empty(Session::get('playlist:' . $playerMachine->playlistId()))) {
            Session::put('playlist:' . $playerMachine->playlistId(), $songs);
        }

        $currentSong = array_shift($songs);

        Session::put('playlist:' . $playerMachine->playlistId(), $songs);

        SongQueueStarted::dispatch(Song::where('external_id', $currentSong)->first());

        $playerMachine->context()->setState($playingState = app(PlayingState::class));

        $this->gateway->changeDevice($playerMachine->deviceId());
        return app(SpotifyGatewayInterface::class)->startSong($playerMachine->deviceId(), 'spotify:track:' . $currentSong);
    }

    public function pause(PlayerMachineContext $context)
    {
        //
    }

    public function next(PlayerMachine $playerMachine)
    {

    }
}
