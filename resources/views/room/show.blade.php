@extends('layouts.app')

@section('content')
    <room
        name="{{$room->name}}"
        rkey="{{$room->getKey()}}"
        code="{{$roomCode}}"
        access_token="{{\Auth::user()->access_token}}"
        existing_player_id="{{$room->deviceId}}"
        has_parent="{{\Auth::user()->hasParent()}}"
    ></room>
@endsection
