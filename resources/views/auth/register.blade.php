@extends('layouts.app')

@section('content')
<div id="landing-container">

    <div class="landing-background">
        <div class="auth-container">
            <header>
                <img src="../images/plateful-logo.svg" alt="Plateful Logo">
                <h1>Complete to create your account</h1>
            </header> 

            <form id="register-form" method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <label for="first-name">First Name</label>
                    <input class="input-box" type="text" id="first-name" name="first_name">
                </div>
                
                <div>
                    <label for="last-name">Last Name</label>
                    <input class="input-box" type="text" id="last-name" name="last_name">
                </div>
            
                <div>
                    <label for="email">Email Address</label>
                    <input class="input-box" type="text" id="email" name="email">
                </div>

                <div>
                    <label for="date-of-birth">Date of Birth (DD/MM/YYYY)</label>
                    <input class="input-box" type="text" id="date-of-birth" name="date_of_birth">
                </div>

                <div>
                    <label for="password">Password</label>
                    <input class="input-box" type="password" id="password" name="password">
                </div>

                <div>
                    <label for="confirm-password">Confirm Password</label>
                    <input class="input-box" type="password" id="confirm-password" name="password_confirmation">
                </div>

                <button type="button" id="register-btn" class="btn btn-primary center-content">{{ __('Sign Up') }}</button>

                <div id="register-success" class="alert alert-success">
                    Registered Successfully. You will be redirected shortly...
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

