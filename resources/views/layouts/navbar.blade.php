<nav class="navbar navbar-expand-md navbar-{{ ($darkmode ?? false) ? 'dark' : 'light' }} bg-{{ ($darkmode ?? false) ? 'dark' : 'light' }} shadow-sm">
    <div class="container">
        @guest
            <a class="navbar-brand" href="/">
                <img src="/favicon.ico" width="30" height="30" class="d-inline-block align-top" alt="">
                {{ config('app.name', 'SelfAccounting') }}
            </a>
        @else
            <a class="navbar-brand" href="/summary">
                <img src="/favicon.ico" width="30" height="30" class="d-inline-block align-top" alt="">
                {{ config('app.name', 'SelfAccounting') }}
            </a>
        @endguest

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @auth
                    <li>
                        <a class="nav-link" href="/income">
                            <i class="fas fa-sign-in-alt"></i>
                            {{ __('Income') }}
                        </a>
                    </li>

                    <li>
                        <a class="nav-link" href="/outcome">
                            <i class="fas fa-sign-out-alt"></i>
                            {{ __('Outcome') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/settings">
                            <i class="fas fa-cog"></i>
                            {{ __('Settings') }}
                        </a>
                    </li>

                    @can("isAdmin", auth()->user())
                    <li class="nav-item dropdown">
                        <a id="admin-dropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-lock"></i>
                            Admin
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="admin-dropdown">
                            <a class="dropdown-item" href="/admin/users">
                                <i class="fas fa-users"></i>
                                Users
                            </a>
                        </div>
                    </li>
                    @endcan
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->username }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/profile">
                                <i class="fas fa-user"></i>
                                View Profile
                            </a>

                            <hr class="my-2">

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                <i class="fas fa-key"></i>
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest

                <li class="nav-item my-auto" id="darkmode-switcher">
                    <div class="nav-link h5 my-auto" id="sun-moon">
                        <i class="fas fa-{{ ($darkmode ?? false) ? 'sun' : 'moon' }}"></i>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
