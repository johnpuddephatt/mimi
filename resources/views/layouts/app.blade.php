<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="refresh" content="86400">

        <title>{{ config('app.name', 'Joy of Languages') }} {{ app()->version() }}</title>

        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="{{ mix('css/vue.css') }}" rel="stylesheet">
        {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css" rel="stylesheet"> --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.2.45/css/materialdesignicons.css" integrity="sha256-mHVnxh+7TPhWR15Mw9aeGEXnvJo75EqKr/zwS4pGIak=" crossorigin="anonymous" />
        <!-- Scripts -->
        <script defer src="{{ mix('js/app.js') }}"></script>


    </head>
    <body class="has-navbar-fixed-top">
        <div id="app">
            <nav class="navbar is-transparent is-fixed-top is-spaced">
                <div class="container">
                    <div class="navbar-brand">
                        <a href="{{ url('/') }}" class="navbar-item">
                           <img src="{{ asset('/images/logo.png') }}" alt="">
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
                                        <a class="navbar-item" href="{{ route('admin.emails') }}">
                                          Emails
                                        </a>
                                        <a class="navbar-item" href="/admin/logs">
                                          Logs
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
            <main>
              @yield('content')
            </main>
            <footer class="footer">
                <div class="column has-text-centered">
                  <p class="menu-label">©{{ date("Y") }} Joy of Languages SRL<br> Milano, Via Pietro Mascagni n.31, 11461250968, MI - 2604182</p>
                </div>
            </footer>
        </div>

      @if(session()->has('message'))
        <div class="notices is-bottom">
          <div role="alertdialog" class="toast is-success is-top is-autodismiss" style="">
            <div class="text">{{ session()->get('message') }}</div>
          </div>
        </div>
      @endif

      <script type="text/javascript">!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});</script>
      <script type="text/javascript">
        window.Beacon('config', {
          hideFABLabelOnMobile: true,
          color: '#33c2cf',
          display:  {
            zIndex: '50',
            style: 'iconAndText',
            iconImage: 'buoy',
            text: 'Need help?'
          }
        });
        window.Beacon('init', 'd665d709-87f7-427b-994a-9089787faf0c')
      </script>
  </body>
</html>
