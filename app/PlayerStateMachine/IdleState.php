<?php

namespace App\PlayerStateMachine;

use App\Events\SongQueueStarted;
use App\Events\SongQueueUpdated;
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
        $sessionName = $playerMachine->room()->queueSessionName();

        if(empty(Session::get($sessionName))) {
            Session::put($sessionName, $songs);
        }

        $currentSong = array_shift($songs);

        Session::put($sessionName, $songs);

        SongQueueStarted::dispatch(Song::where('external_id', $currentSong)->first());
        SongQueueUpdated::dispatch( $songs );

        $playerMachine->context()->setState($playingState = app(PlayingState::class));

        $this->gateway->changeDevice($playerMachine->deviceId());
        return app(SpotifyGatewayInterface::class)->startSong($playerMachine->deviceId(), 'spotify:track:' . $currentSong);
    }

    public function pause(PlayerMachine $playerMachine)
    {
        //
    }

    public function next(PlayerMachine $playerMachine)
    {

    }
}
