<header class="main-header">
    <!-- Logo -->
    <a href="/admin" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">{{ config('app.name', 'Inovatica') }}</span>
        <!-- logo for regular state and mobile devices -->
        <!-- FIXME: for now inline, but sooon -->
        <span style="margin-top: .5rem;" class="logo-lg programiaki-logo"><img src="{{asset('logos/logo_sm.png')}}" class="img-responsive" ></span>
        <span style="margin-top: -2rem; margin-left: -4.5rem; position: absolute; transform: scale(.6);" class="powered-by">Powered by<i class="fa fa-eercast"></i>{{ config('app.name', 'Inovatica') }}</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle hidden-lg hidden-md hidden-sm" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ $user->email }} <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-footer">
                            <div class="pull-left">
                                {{--<a href="/profile" class="btn btn-default btn-flat">Profil</a>--}}
                            </div>
                            <div class="pull-right">
                                <a href="{{ url('/logout') }}" role="button" class="btn btn-default btn-flat"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out"></i> Wyloguj
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>

                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>