@extends('layouts.app')

@section('content')
        <div class="max-w-3xl h-screen mx-auto flex items-center content-center">
            @if(!\Auth::check())
                <a href="/connect">Login</a>
                <div class="mx-auto w-1/2">
                    <div class="flex justify-around flex-auto">
                        <form action="/rooms" method="POST" class="mt-3">
                            {{ csrf_field() }}
                            <input name="room" placeholder="Create Room" />
                            <input type="submit" value="Create" />
                        </form>

                        <form action="room/join" method="POST" class="mt-3">
                            {{ csrf_field() }}
                            <input name="room" placeholder="Join Room" />
                            <input type="submit" value="Join" />
                        </form>
                    </div>
                </div>
                @else
                <span class="align-start">Welcome, {{\Auth::user()->name}}</span> | <span><a href="/logout">Logout</a></span>

                <h2>Create Room</h2>
                <form action="rooms" method="POST">
                    {{ csrf_field() }}
                    <input name="room" />
                    <button>Save</button>
                </form>

                <ul>
                @foreach(\Auth::user()->rooms as $room)
                        <li><a href="/rooms/{{$room->getKey()}}">{{$room->name}}</a></li>
                @endforeach
                </ul>

            @endif
        </div>
@endsection
