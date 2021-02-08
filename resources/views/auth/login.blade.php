@extends('layouts.app')

@section('content')
<div id="landing-container">
    
    <div class="landing-background">
        <div class="auth-container">
            <header>
                <img src="../images/plateful-logo.svg" alt="Plateful Logo">
                <h1>Please log in to your account</h1>
            </header>       

            <form method="POST" id="log-in-form" action="{{ route('login') }}">
                @csrf 
                <div>
                    <label class="" for="email">{{ __('Email Address') }} </label>
                    <input class="input-box" name="email" value="user@plateful.com" type="email" id="email">
                </div>

                <div>
                    <label class="" for="password">{{ __('Password') }}</label>
                    <input class="input-box" name="password" value="secret123" type="password" id="password">
                </div>
                
                <div class="form-inline">
                    <div class="custom-checkbox">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span><label for="remember">Remember Me</label></span>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="btn-tertiary">
                            Forgot Password
                        </a>
                    @endif
                </div>
                
                <div class="landing-form-buttons">
                    <button type="submit" class="btn btn-primary">Log In</button>
                    <a href="/register" tabindex="-1"><button type="button" class="btn btn-secondary">Sign Up</button></a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

