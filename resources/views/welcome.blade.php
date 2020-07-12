@extends('layouts.app')

@section('content')
        @if(!\Auth::check())
            <div class="bg-grey-lighter h-screen main-background flex flex-col">
                <h1 class="logo bold"><span>POOL</span> PARTY</h1>
                <div class="form-wrapper">
                    <form action="room/join" method="POST" class="login-form flex flex-col">
                        {{ csrf_field() }}
                        <input name="room" placeholder="ROOM CODE" />
                        <input name="guest_user" placeholder="Name" />
                        <input type="submit" value="Jump on in" />
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
