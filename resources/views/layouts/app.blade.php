<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} {{ app()->version() }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css" rel="stylesheet">
        <!-- Scripts -->
        <script defer src="{{ asset('js/app.js') }}"></script>

    </head>
    <body class="has-navbar-fixed-top">
        <div id="app">
            <nav class="navbar is-transparent is-fixed-top is-spaced">
                <div class="container">
                    <div class="navbar-brand">
                        <a href="{{ url('/') }}" class="navbar-item">
                           <img src="/images/logo.png" alt="">
                        </a>

                        <div class="navbar-burger burger" data-target="navMenu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>

                    <div class="navbar-menu" id="navMenu">
                        <div class="navbar-start"></div>

                        <div class="navbar-end">
                            @if (Auth::guest())
                            @else
                                <div class="navbar-item has-dropdown is-hoverable">
                                    <a class="navbar-link" href="#">
                                      {{ Arr::random(['Ciao', 'Salve', 'Pronto']) }}, {{ Auth::user()->first_name }}!
                                      @if(Auth::user()->is_admin)<span class="tag is-success">Admin</span>@endif
                                    </a>

                                    <div class="navbar-dropdown">
                                      @if(Auth::user()->is_admin)
                                        <a class="navbar-item" href="{{ route('admin') }}">
                                          Admin
                                        </a>
                                      @endif
                                        <a class="navbar-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>
            @yield('content')
            <footer class="footer">
                <div class="column has-text-centered">
                  <p class="menu-label">© 2019 Joy of Languages Limited.<br>Company number 11811277 registered in England & Wales.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
