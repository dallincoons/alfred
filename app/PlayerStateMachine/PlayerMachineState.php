<?php

namespace App\PlayerStateMachine;

interface PlayerMachineState
{
    public function play(PlayerMachine $playerMachine, array $songs): bool;
    public function playSong(PlayerMachine $playerMachine, string $song): bool;
    public function pause(PlayerMachine $playerMachine);
    public function next(PlayerMachine $playerMachine);
    public function resume(PlayerMachine $playerMachine);
}
