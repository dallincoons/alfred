@extends('layouts.app')

@section('content')
        @if(!\Auth::check())
            <div class="bg-grey-lighter h-screen main-background flex flex-col">
                <div class="logo-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 815.45 102.26">
                        <path class="cls-party" d="M421.47,63.3H405V97.08h-18.9v-93h35.33c22.32,0,32.24,13.64,32.24,29.6C453.71,49.81,443.79,63.3,421.47,63.3Zm-3.1-45.72H405V49.81h13.33c12.25,0,16.59-7.28,16.59-16.12C435,25,430.62,17.58,418.37,17.58Z"/>
                        <path class="cls-party" d="M524.07,97.08l-9.45-22.93H477L467.5,97.08H449.22l39.52-93h14.72l39.52,93Zm-28.2-69.89L482.07,61H509.5Z"/>
                        <path class="cls-party" d="M621.09,98.17c-11.15,0-20.14-4.81-26.81-18.29L584.36,60H573.51v37h-18.9v-93h36.57c22,0,31.46,13,31.46,28.06,0,13.17-7.43,23.4-20.45,26.34l9.14,16c4.65,8.05,9,10.84,15.5,10.84a15.52,15.52,0,0,0,5.27-.77V96.46A39.49,39.49,0,0,1,621.09,98.17ZM573.51,46.71h14.57c11.78,0,16-6.2,16-14.41,0-8.37-4.19-14.72-16-14.72H573.51Z"/>
                        <path class="cls-party" d="M679.52,18.82V97.08h-18.9V18.82H629.15V4.09H711V18.82Z"/>
                        <path class="cls-party" d="M775.33,59.89V97.08H756.42V59.89L720,4.09h20.62l25.57,40.45L791.76,4.09h20Z"/>
                        <path class="cls-pool" d="M37.34,63.84H20.91V97.62H2v-93H37.34c22.31,0,32.23,13.64,32.23,29.61S59.65,63.84,37.34,63.84Zm-3.1-45.72H20.91V50.36H34.24c12.24,0,16.58-7.29,16.58-16.12S46.48,18.12,34.24,18.12Z"/>
                        <path class="cls-pool" d="M130.48,100.26C102,100.26,79.8,79.65,79.8,51.13S102,2,130.48,2,180.7,22.61,180.7,51.13,158.85,100.26,130.48,100.26Zm0-83.23c-18.59,0-30.84,14-30.84,34.1s12.25,34.1,30.84,34.1c18.29,0,30.38-14,30.38-34.1S148.77,17,130.48,17Z"/>
                        <path class="cls-pool" d="M243,100.26c-28.51,0-50.67-20.61-50.67-49.13S214.49,2,243,2s50.22,20.61,50.22,49.13S271.37,100.26,243,100.26ZM243,17c-18.59,0-30.84,14-30.84,34.1s12.25,34.1,30.84,34.1c18.29,0,30.38-14,30.38-34.1S261.29,17,243,17Z"/>
                        <path class="cls-pool" d="M309.34,97.62v-93h18.91V82.9h41.69V97.62Z"/>
                    </svg>
                </div>
                <div class="form-wrapper">
                    <form action="room/join" method="POST" class="login-form flex flex-col">
                        {{ csrf_field() }}
                        <input name="room" placeholder="ROOM CODE" class="login-input border-grey-dark" />
                        <input name="guest_user" placeholder="NAME" class="login-input border-grey-dark" />
                        <input type="submit" value="Jump on in" class="login-button bg-grey-dark"/>
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
