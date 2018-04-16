@extends('layouts.app')

@section('content')
    <room
        name="{{$room->name}}"
        rkey="{{$room->getKey()}}"
        code="{{$roomCode}}"
        access_token="{{\Auth::user()->access_token}}"
    ></room>
@endsection
