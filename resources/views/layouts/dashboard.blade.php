<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <script src="{{ mix('js/app.js') }}"></script>
        <!--<script src="{{ asset('js/app.js') }}" defer></script>-->

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">

        <!-- Styles -->
        <!-- only use for development-->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="page-container {{ Session::get('sidebarExpanded') ? '' : 'no-sidebar' }}">
            <header class="nav-header">
                <button class="nav-menu-icon sidenav-icon" aria-label="Toggle Navigation Menu" aria-haspopup="true" aria-expanded="true"></button>
                <h1 class="brand-name">PLATEFUL</h1>
                <button class="nav-menu-icon hamburger-icon" aria-label="Toggle Navigation Menu" aria-haspopup="true" aria-expanded="false"></button>
                <div class="user-image">
                    <img src="{{ asset('images/icons/user-placeholder.svg') }}">    
                </div>
            </header>

            <nav aria-label="main-navigation" class="main-navigation">
                <ul>
                    <!--
                    <li>
                        <a href="/meal-plans"><img src="{{ asset('images/icons/utencils.svg') }}" alt="Knife and Fork Icon">Meal Plans</a> 
                    </li>
                    -->
                    <li>
                        <a href="/recipes"><img src="{{ asset('images/icons/book-open.svg') }}" alt="Recipe Book Icon">Recipes</a>
                    </li>
                    <li>
                        <a href="/ingredients"><img src="{{ asset('images/icons/pizza.svg') }}" alt="Ingredient Icon">Ingredients</a>
                    </li>
                    <li>
                        <a class="log-out" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('log-out-form').submit();">
                                <img src="{{ asset('images/icons/log-out.svg') }}" alt="Log Out Icon">
                                Log Out
                        </a>
                        <form id="log-out-form" action="{{ route('logout') }}" method="POST">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </nav>

            <main>
                @yield('content')
            </main>
        </div>
    </body>
</html>