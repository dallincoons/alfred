@extends('layouts.app')

@section('content')
    <room
        username="{{\Auth::user()->name}}"
        name="{{$room->name}}"
        rkey="{{$room->getKey()}}"
        code="{{$roomCode}}"
        access_token="{{\Auth::user()->access_token}}"
        existing_player_id="{{$room->deviceId}}"
        has_parent="{{\Auth::user()->hasParent()}}"
        raw_room_songs="{{$songs}}"
    ></room>
@endsection
