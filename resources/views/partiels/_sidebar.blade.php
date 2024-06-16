<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User Profile-->
                <li>
                    <!-- User Profile-->
                    <div class="user-profile d-flex no-block dropdown m-t-20">
                        <div class="user-pic"><img src="{{Auth::user()->image}}" alt="users" class="rounded-circle" width="40" /></div>
                        <div class="user-content hide-menu m-l-10" style="font-size: 0.75em;">
                            <a href="javascript:void(0)" class="" id="Userdd" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <h5 style="font-size: 1.5em;" class="m-b-0 user-name font-medium">{{ Auth::user()->name }}<i class="fa fa-angle-down"></i></h5>
                                <span class="op-5 user-email">{{ Auth::user()->email }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Userdd">
                                <a class="dropdown-item" href="/profil" ><i class="ti-user m-r-5 m-l-5"></i> Mon Profil</a>
                                <a class="dropdown-item" href="{{route('facture.index')}}"><i class="ti-wallet m-r-5 m-l-5"></i> Facture</a>
                                <a class="dropdown-item" href="{{route('inbox.index')}}"><i class="ti-email m-r-5 m-l-5"></i> Inbox
                                    <span class="nbrNotify" style="
                                    left: 90px;
                                    top: 105px;
                                    position: absolute;
                                    " ><b>{{auth()->user()->unreadNotifications->count()}}</b></span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-settings m-r-5 m-l-5"></i> Parametre</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"  href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off m-r-5 m-l-5"></i>
                                    Deconnexion
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                     @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End User Profile-->
                </li>


                @can('edit-users')
                <li class="p-15 m-t-10">
                    <a data-toggle="modal" data-target="#addNewUserModal" class="btn btn-block create-btn text-white no-block d-flex align-items-center">
                        <i class="fa fa-plus-square"></i>
                        <span class="hide-menu m-l-5">Nouveau Utilisateur</span>
                    </a>
                </li>
                @endcan
                <!-- User Profile-->
                @can('client-admin')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>

                @endcan
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/profil" aria-expanded="false"><i class="mdi mdi-account-network"></i><span class="hide-menu">Profile</span></a></li>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/commandes" aria-expanded="false"><i class="mdi mdi-package-variant"></i>
                        @can('manage-users')
                            <span class="hide-menu">Commandes</span>
                            <span class="badge badge-danger">{{App\Commande::where('statut','Confirmé sous RDV')->count()}}</span>
                        @endcan
                        @cannot('manage-users')
                        <span class="hide-menu">Gestion des colis</span>
                        @endcannot
                    </a>
                </li>
                @can('client-admin-personnel-superviseur,livreur')
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('ramassage.index')}}" aria-expanded="false">
                        <i class="mdi mdi-inbox"></i>
                        <span class="hide-menu">
                            Ramassage
                            @can('admin')
                            <span class="badge badge-danger">{{App\Ramassage::whereIn('statut',array('En attente de ramassage','Ramassé par le livreur'))->count()}}</span>
                            @endcan
                            @can('superviseur')
                            <span class="badge badge-danger">{{App\Ramassage::where('statut','Ramassé par le livreur')->where('city',Auth::user()->ville)->count()}}</span>
                            @endcan
                            @can('client')
                            <span class="badge badge-danger">{{App\Ramassage::whereIn('statut',array('En attente de ramassage','Ramassé par le livreur'))->where('user_id',Auth::user()->id)->count()}}</span>
                            @endcan
                            @can('livreur')
                            <span class="badge badge-danger">{{App\Ramassage::where('statut','En attente de ramassage')->where('livreurId',Auth::user()->id)->count()}}</span>
                            @endcan
                        </span>
                    </a>
                </li>
                @endcan

                @can('admin-superviseur')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('transfert.index')}}" aria-expanded="false"><i class="mdi mdi-swap-horizontal"></i><span class="hide-menu">Demande de transfert</span></a></li>
                @endcan
                {{-- <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('retour.index')}}" aria-expanded="false"><i class="mdi mdi-twitter-retweet"></i><span class="hide-menu">Gestion des retours</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('transfert.retour.index')}}" aria-expanded="false"><i class="mdi mdi-truck-delivery"></i><span class="hide-menu">Transfert des retours</span></a></li>
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('bonretour.index')}}" aria-expanded="false"><i class="mdi mdi-flip-to-back"></i><span class="hide-menu">Bon de retour</span></a></li> --}}

                @cannot('livreur-superviseur')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('bonlivraison.index')}}" aria-expanded="false"><i class="mdi mdi-note-text"></i><span class="hide-menu">Bon de livraison</span></a></li>
                @can('client-admin')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('facture.index')}}" aria-expanded="false"><i class="mdi mdi-newspaper"></i><span class="hide-menu">Facture</span></a></li>

                @endcan

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('archive.index')}}" aria-expanded="false"><i class="mdi mdi-archive"></i><span class="hide-menu">Archive</span></a></li>

                @endcannot
                @can('livreur-admin')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                    href=
                    @can('edit-users')
                    "{{route('caisse.index')}}"
                    @endcan
                    @can('livreur')
                    "{{route('caisse.livreur',['id'=> Auth::user()->id])}}"
                    @endcan
                    aria-expanded="false"><i class="mdi mdi-cash-multiple"></i><span class="hide-menu">Caisse</span></a></li>
                @endcan

                @can('gestion-stock')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('produit.index')}}" aria-expanded="false"><i class="mdi mdi-package-variant-closed"></i><span class="hide-menu"> Gestion du stock</span></a></li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('reception.index')}}" aria-expanded="false"><i class="mdi mdi-truck"></i>
                        <span class="hide-menu">
                            Ramassage stock
                            @can('manage-users')
                            <span class="badge badge-danger">{{App\Reception::where('etat','Envoyé')->count()}}</span>
                            @endcan
                        </span>
                    </a>
                </li>
                @endcan
                @can('manage-users')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('Relance.index')}}" aria-expanded="false"><i class="mdi mdi-creation"></i><span class="hide-menu">Commandes VIP</span></a></li>
                @endcan
                @can('edit-users')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('ville.index')}}" aria-expanded="false"><i class="mdi mdi-castle"></i><span class="hide-menu">Gestion des villes</span></a></li>

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.users.index')}}" aria-expanded="false"><i class="mdi mdi-account-switch"></i><span class="hide-menu">Utilisateurs
                @if ($nouveau > 0)
                <span class="badge badge-danger">{{$nouveau}}</span>
                @endif
                </span></a></li>
                @endcan
                @can('delete-commande')
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('reclamation.index')}}" aria-expanded="false"><i class="fab fa-buffer"></i>
                        <span class="hide-menu">
                            @can('manage-users')
                            Gestion des tickets <span class="badge badge-danger">{{App\Reclamation::where('etat',0)->count()}}</span>
                            @endcan
                            @cannot('manage-users')
                                Mes tickets
                            @endcannot
                        </span>
                    </a>
                </li>
                @endcan
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

@can('edit-users')
<div class="modal fade" id="addNewUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 720px;">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Choisissez le rôle de l'utilisateur</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="container">
                <div class="row">

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label>
                        <input type="radio" name="role" value="admin" selected checked class="card-input-element" />
                          <div class="card card-default card-input">
                            <div class="card-header" style="font-weight: bold">Administrateur</div>
                            <div class="card-body">
                                <img style="width: 100%;" src="{{ asset('assets/images/admin.JPG') }}" alt="Administrateur" />
                            </div>
                          </div>
                      </label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <label>
                          <input type="radio" name="role" value="personnel" class="card-input-element" />
                            <div class="card card-default card-input">
                              <div class="card-header" style="font-weight: bold">Personnel</div>
                              <div class="card-body">
                                  <img style="width: 100%;" src="{{ asset('assets/images/personnel.JPG') }}" alt="Personnel" />
                              </div>
                            </div>
                        </label>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-4">
                        <label>
                          <input type="radio" name="role" value="superviseur" class="card-input-element" />
                            <div class="card card-default card-input">
                              <div class="card-header" style="font-weight: bold">Superviseur</div>
                              <div class="card-body">
                                  <img style="width: 100%;" src="{{ asset('assets/images/superviseur.JPG') }}" alt="Superviseur" />
                              </div>
                            </div>
                        </label>
                      </div>
                </div>
                <div class="row">

                    <div class="col-md-4 col-lg-4 col-sm-4">
                      <label>
                        <input type="radio" name="role" value="livreur" class="card-input-element" />
                          <div class="card card-default card-input">
                            <div class="card-header" style="font-weight: bold">Livreur</div>
                            <div class="card-body">
                                <img style="width: 100%;" src="{{ asset('assets/images/livreur.JPG') }}" alt="Livreur" />
                            </div>
                          </div>
                      </label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">

                      <label>
                        <input type="radio" name="role" value="ramassage" class="card-input-element" />

                          <div class="card card-default card-input">
                            <div class="card-header" style="font-weight: bold">Ramassage/Livraison</div>
                            <div class="card-body">
                                <img style="width: 100%;" src="{{ asset('assets/images/ramassage.JPG') }}" alt="ramassage" />
                            </div>
                          </div>
                      </label>

                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">

                      <label>
                        <input type="radio" name="role" value="stock" class="card-input-element" />

                          <div class="card card-default card-input">
                            <div class="card-header" style="font-weight: bold">Stockage/Livraison</div>
                            <div class="card-body">
                                <img style="width: 100%;" src="{{ asset('assets/images/stock.JPG') }}" alt="stock" />
                            </div>
                          </div>
                      </label>

                    </div>
                </div>

              </div>
        </div>
        <div class="modal-footer">
            <a type="button" onclick="handleRoleValidate()" class="btn btn-primary" style="color: white">Valider</a>
        <button type="button" class="btn btn-rafex-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
    </div>
</div>
@endcan

