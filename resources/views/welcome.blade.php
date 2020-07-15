@extends('layouts.app')

@section('content')
        @if(!\Auth::check())
            <div class="flex flex-col h-screen justify-between bg-gradient text-white">
                <p class="p-3 text-2xl">alfred.fm</p>
                <div class="join self-center w-3/5 content-center">
                    <form action="room/join" method="POST" class="join-form flex text-lg content-center w-full">
                        {{ csrf_field() }}
                        @if ($errors)
                            <p>Incorrect room code, silly goose</p>
                        @endif
                        <input name="room" placeholder="ROOM CODE" class="join-input bg-transparent border-bottom border-white text-white text-capitalize font-bold text-5xl w-3/4" />
                        <input name="guest_user_name" placeholder="Name" class="join-input bg-transparent border-bottom border-white text-white text-capitalize font-bold text-5xl w-3/4" value="{{ old('guest_user_name') }}" />
                        <input type="submit" value="Join" class="join-input w-1/5 h-12 self-end font-bold text-3xl text-purple-darkest cursor-pointer bg-button"/>
                    </form>
                </div>
                <div class="self-end p-3 text-lg">

                    <a href="/connect" class="text-white">Login & Create Room</a>
                </div>
            </div>
        @else
            <account raw_user="{{\Auth::user()->load('rooms')}}"></account>

        @endif

@endsection
