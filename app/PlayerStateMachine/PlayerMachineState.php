<?php

namespace App\PlayerStateMachine;

interface PlayerMachineState
{
    public function play(PlayerMachine $playerMachine, array $songs): bool;
    public function pause(PlayerMachineContext $context);
    public function next(PlayerMachine $playerMachine);
}
