@extends('layouts.app')

@section('content')
        @if(!\Auth::check())
            <div class="flex flex-col h-screen justify-between bg-gradient text-white">
                <p class="p-3 text-2xl">evesdrop.fm</p>
                <div class="self-center w-3/5 sm\:w-1/2 h-24 content-center">
                    <form action="room/join" method="POST" class="flex text-lg content-center w-full">
                        {{ csrf_field() }}
                        <input name="room" placeholder="ROOM CODE" class="bg-transparent border-bottom border-white text-white text-capitalize font-bold text-5xl w-3/4" />
                        <input type="submit" value="Join" class="w-1/5 h-12 self-end font-bold text-3xl text-purple-darkest cursor-pointer bg-button"/>
                    </form>
                </div>
                <div class="self-end p-3 text-lg">

                    <a href="/connect" class="text-white">Login & Create Room</a>
                </div>
            </div>
        @else
            <div class="flex flex-col h-screen bg-gradient">
                <div class="flex text-white justify-between pb-8">
                    <p class="p-3 text-2xl">evesdrop.fm</p>
                    <div class="p-3 text-1xl"><span class="">Welcome, {{\Auth::user()->name}}</span> | <span><a href="/logout" class="text-white no-underline">Logout</a></span></div>
                </div>
                <div class="flex justify-around h-64">
                    <div class="text-white">
                        <h3 class="pb-4 text-2xl">My Rooms</h3>
                        <ul class="list-reset">
                            @foreach(\Auth::user()->rooms as $room)
                                <li class="font-light h-16 text-2xl"><a href="/rooms/{{$room->getKey()}}" class="text-white no-underline">{{$room->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="w-2/5 flex flex-col justify-between">
                        <form action="rooms" method="POST" class="flex flex-col text-lg content-center w-full bg-transparent-white p-6 rounded-lg">
                            {{ csrf_field() }}
                            <input name="room" placeholder="Name Your Room" class="bg-transparent text-2xl border-bottom mb-3"/>
                            <input type="submit" value="Create Room" class="h-6 ">
                        </form>
                        <form action="room/join" method="POST" class="flex flex-col text-lg content-center w-full bg-transparent-white p-6 rounded-lg">
                            {{ csrf_field() }}
                            <input name="room" placeholder="ROOM CODE" class="bg-transparent text-2xl border-bottom mb-3" />
                            <input type="submit" value="Join" class="h-6 "/>
                        </form>
                        @if (count($errors) > 0)
                            <div class="error">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    {{--<div class="">--}}
                        {{--<form action="room/join" method="POST" class="flex flex-col text-lg content-center w-full">--}}
                            {{--{{ csrf_field() }}--}}
                            {{--<input name="room" placeholder="ROOM CODE" class="bg-transparent text-2xl border-bottom mb-3" />--}}
                            {{--<input type="submit" value="Join" class=" "/>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                </div>
            </div>


            @endif
@endsection
