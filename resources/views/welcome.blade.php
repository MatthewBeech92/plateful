@extends('layouts.app')

@section('content')

        <div id="welcome-screen">
            <div class="logo">
                <img src="{{ asset('images/plateful-logo.svg') }}" alt="Plateful Logo" >
            </div>

            @if (Route::has('login'))
                <div class="welcome-nav-links">
                    @auth
                        <a href="{{ url('/home') }}">Go To Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
            <div id="demo">
                <h2>This website is for demonstration purposes only</h2>
                <h3>You can log into the application by visiting the log in page and using the details already entered.</h3>
             </div>
        </div>
   

@endsection

