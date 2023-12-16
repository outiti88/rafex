<header class="topbar" data-navbarbg="skin5">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark"
        style="border-bottom-style: solid;
    border-bottom-color: #467a0f;">
        <div class="navbar-header" data-logobg="skin5">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="{{ route('dashboard') }}">

                <span class="logo-text" style="position: relative; left:20px">

                    <img src="{{ asset('assets/images/logo-light-text.png') }}"
                        style="
                     WIDTH: 110PX;
                 "class="light-logo"
                        alt="homepage" />
                </span>
            </a>

            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                    class="ti-menu ti-close"></i></a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-left mr-auto">
                <!-- ============================================================== -->
                <!-- Search -->
                <!-- ============================================================== -->
                <li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark"
                        href="javascript:void(0)"><i style="
                    color: black;"
                            class="ti-search"></i></a>
                    <form class="app-search position-absolute" method="GET" action="{{ route('commande.search') }}">
                        @csrf
                        <input name="search" type="text" class="form-control"
                            placeholder="Tapez le numero de commande / bon de livraison / facture ou cherchez par statut de commande">
                        <a class="srh-btn"><i style="
                            color: black;"
                                class="ti-close"></i></a>
                        <input type="button" style="display: none">
                </li>
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-right">
                @guest
                    <li class="nav-item">
                        <a style="
                                color: #467a0f !important;
                            "
                            class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a style="
                                    color: #467a0f !important;
                                "
                                class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown" style="display: flex;">
                        <span class="nbrNotify">
                            @if (auth()->user()->unreadNotifications->count() > 99)
                                <b>+99</b>
                            @else
                                <b>{{ auth()->user()->unreadNotifications->count() }}</b>
                            @endif
                        </span>
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic "
                            style="
                                    color: #467a0f !important;
                                    font-size: 1.5em;"
                            href="
                                 @can('client')
                                 {{ route('inbox.index') }}
                                 @endcan"
                            @can('delete-users')
                                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                 @endcan>
                            <i class="fas fa-bell"></i>
                        </a>
                        @can('delete-users')
                            @unless(auth()->user()->unreadNotifications->isEmpty())
                                <ul class="dropdown-menu notify-drop"
                                    style="width: 300px;
                                        position: absolute;
                                        left: -250px;
                                        max-height: 500px;
                                        overflow-y:scroll;
                                         overflow-x:hidden;">
                                    <div class="notify-drop-title"
                                        style="
                                                        margin: 10px;">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">Notifications
                                                (<b>{{ auth()->user()->unreadNotifications->count() }}</b>)</div>
                                            <div class="col-md-6 col-sm-6 col-xs-6 text-right"><a href=""
                                                    class="rIcon allRead" data-tooltip="tooltip" data-placement="bottom"
                                                    title="tümü okundu."><i class="fa fa-dot-circle-o"></i></a></div>
                                        </div>
                                    </div>
                                    <div class="drop-content" style="
                                            margin: 10px;">
                                        @foreach (auth()->user()->unreadNotifications->take(10) as $notification)
                                            @if ($notification->type == 'App\Notifications\newCommande')
                                                <li class="row">
                                                    <a class="hoverNotif"
                                                        href="{{ route('commandes.showFromNotify', [
                                                            'commande' => $notification->data['commande']['id'],
                                                            'notification' => $notification->id,
                                                        ]) }}">
                                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                                            <div class="notify-img"><img width="35" class="rounded-circle"
                                                                    src="{{ $notification->data['user']['image'] }}"
                                                                    alt=""></div>
                                                        </div>
                                                        <div style="font-size: 0.75rem !important;"
                                                            class="col-md-9 col-sm-9 col-xs-9 pd-l0"><a
                                                                href="{{ route('admin.users.edit', $notification->data['user']['id']) }}">{{ $notification->data['user']['name'] }}</a>
                                                            a ajouté une nouvelle commande.
                                                            <p style="font-size: 0.75rem;">N°: <a
                                                                    href="{{ route('commandes.showFromNotify', [
                                                                        'commande' => $notification->data['commande']['id'],
                                                                        'notification' => $notification->id,
                                                                    ]) }}">{{ $notification->data['commande']['numero'] }}</a>
                                                            </p>
                                                            <p class="proile-rating">
                                                                {{ date_format($notification->created_at, 'Y/m/d') }}<span>
                                                                    {{ date_format($notification->created_at, 'H:i:s') }}</span></p>
                                                        </div>
                                                    </a>
                                                </li>
                                            @else
                                                <li class="row">
                                                    <a class="hoverNotif"
                                                        href="{{ route('reception.showFromNotify', [
                                                            'reception' => $notification->data['reception']['id'],
                                                            'notification' => $notification->id,
                                                        ]) }}">
                                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                                            <div class="notify-img"><img width="35" class="rounded-circle"
                                                                    src="{{ $notification->data['user']['image'] }}"
                                                                    alt=""></div>
                                                        </div>
                                                        <div style="font-size: 0.75rem !important;"
                                                            class="col-md-9 col-sm-9 col-xs-9 pd-l0"><a
                                                                href="{{ route('admin.users.edit', $notification->data['user']['id']) }}">{{ $notification->data['user']['name'] }}</a>
                                                            a envoyé une reception.
                                                            <p style="font-size: 0.75rem;">Ref°: <a
                                                                    href="{{ route('reception.showFromNotify', [
                                                                        'reception' => $notification->data['reception']['id'],
                                                                        'notification' => $notification->id,
                                                                    ]) }}">{{ $notification->data['reception']['reference'] }}</a>
                                                            </p>
                                                            <p class="proile-rating">
                                                                {{ date_format($notification->created_at, 'Y/m/d') }}<span>
                                                                    {{ date_format($notification->created_at, 'H:i:s') }}</span></p>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach


                                    </div>
                                    <div class="notify-drop-footer text-center"
                                        style="
                                            padding-top: 15px;
                                                ">
                                        <a class="notify" href="{{ route('inbox.index') }}"><i class="fa fa-eye"></i> Voir
                                            Tous</a>
                                    </div>
                                </ul>
                            @endunless
                        @endcan

                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ Auth::user()->image }}" alt="user" class="rounded-circle"
                                width="31"></a>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated">
                            <a class="dropdown-item" href="/profil""><i class="ti-user m-r-5 m-l-5"></i>
                                {{ Auth::user()->name }} </a>
                            <a class="dropdown-item" href="{{ route('inbox.index') }}"><i
                                    class="ti-email m-r-5 m-l-5"></i>
                                Inbox
                                @can('delete-users')
                                    <span class="badge badge-pill badge-danger">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endcan
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off m-r-5 m-l-5"></i> Deconnexion</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
</header>
