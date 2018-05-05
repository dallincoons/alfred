@extends('layouts.app')

@section('content')
    <room
        name="{{$room->name}}"
        rkey="{{$room->getKey()}}"
        code="{{$roomCode}}"
        access_token="{{\Auth::user()->access_token}}"
        existing_player_id="{{$room->existingDeviceId}}"
        has_parent="{{\Auth::user()->hasParent()}}"
        raw_room_songs="{{$songs}}"
    ></room>
@endsection
