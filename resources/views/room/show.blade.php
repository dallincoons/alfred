@extends('layouts.app')

@section('content')
    <room
        name="{{$room->name}}"
        rkey="{{$room->key}}"
        code="{{$roomCode}}"
        access_token="{{\Auth::user()->access_token}}"
    ></room>
@endsection
