<?php

namespace Tests\Unit\PlayerStateMachine;

use App\PlayerStateMachine\PausedState;
use App\PlayerStateMachine\PlayerMachine;
use App\PlayerStateMachine\PlayingState;
use App\Room;
use App\Song;
use Tests\Fakes\ExternalSongFaker;
use Tests\TestCase;

class PlayerStateMachineTest extends TestCase
{
    /** @test */
    public function it_transitions_from_idle_state_to_play()
    {
        $machine = new PlayerMachine($room = factory(Room::class)->create());

        $machine->play([factory(Song::class)->create()->external_id]);

        $machine = new PlayerMachine($room);

        $this->assertInstanceOf(PlayingState::class, $machine->currentState());
    }

    /** @test */
    public function it_transitions_from_play_to_next()
    {
        $machine = new PlayerMachine(factory(Room::class)->create());

        $machine->play([factory(Song::class)->create()->external_id]);

        $this->assertInstanceOf(PlayingState::class, $machine->currentState());
    }

    /** @test */
    public function it_transitions_from_playing_state_to_pause()
    {
        $machine = new PlayerMachine(factory(Room::class)->create());

        $machine->play([factory(Song::class)->create()->external_id]);
        $machine->pause();

        $this->assertInstanceOf(PausedState::class, $machine->currentState());
    }

    /** @test */
    public function it_transitions_from_paused_state_to_playing()
    {
        $machine = new PlayerMachine(factory(Room::class)->create());

        $machine->play([factory(Song::class)->create()->external_id]);
        $machine->pause();
        $machine->resume();

        $this->assertInstanceOf(PlayingState::class, $machine->currentState());
    }

    /** @test */
    public function it_throws_exception_if_next_is_selected_on_empty_playlist()
    {
        $machine = new PlayerMachine(factory(Room::class)->create());

        $this->expectExceptionMessage('There are no songs in the playlist');
        $machine->next();
    }

    /** @test */
    public function it_transitions_from_idle_to_next()
    {
        $machine = new PlayerMachine($room = factory(Room::class)->create());
        $room->addSong(ExternalSongFaker::any());

        $machine->next();

        $this->assertInstanceOf(PlayingState::class, $machine->currentState());
    }

    /** @test */
    public function it_transitions_from_paused_to_next()
    {
        $machine = new PlayerMachine($room = factory(Room::class)->create());
        $room->addSong(ExternalSongFaker::any());

        $machine->play([factory(Song::class)->create()->external_id]);
        $machine->pause();

        $machine->next();

        $this->assertInstanceOf(PlayingState::class, $machine->currentState());
    }
}
