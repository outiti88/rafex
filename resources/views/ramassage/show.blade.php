@extends('racine')

@section('title')
Ramassage | {{$ramassage->reference}}
@endsection

@section('style')
    <style>
        .noselect {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
            -ms-user-select: none;
                user-select: none;
        }

        .multiselect {
            width: 170px;
            font-size: 15px;
            padding-bottom: 4px;
            border-radius: 3px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: 0.2s;
            outline: none;
        }

        .multiselect:hover {
            border: 1px solid rgba(0, 0, 0, 0.3);
        }

        .multiselect.active {
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
            border-bottom: 1px solid transparent;
        }

        .multiselect > .title {
            cursor: pointer;
            height: 30px;
            padding: 6px;
        }

        .multiselect > .title > .text {
            max-height: 25px;
            display: block;
            float: left;
            overflow: hidden;
            line-height: 1.3em;
            font-size: 12px;
        }

        .multiselect > .title > .expand-icon,
        .multiselect > .title > .close-icon {
            float: right;
            border-radius: 50%;
            padding: 0 4px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            font-weight: 700;
            transition: 0.2s;
            display: none;
        }

        .multiselect.selection > .title > .expand-icon {
            display: none;
        }

        .multiselect > .title > .expand-icon,
        .multiselect.selection > .title > .close-icon {
            display: block;
        }

        .multiselect > .title > .close-icon:hover {
            border: 1px solid rgba(0, 0, 0, 0.3);
            background: rgb(211 70 63);
            color: #fff;
        }

        .multiselect > .container {
            max-height: 200px;
            overflow: auto;
            margin-top: 4px;
            margin-left: -1px;
            width: 170px;
            transition: 0.2s;
            position: absolute;
            z-index: 99;
            background: #fff;
            border: 1px solid transparent;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .multiselect.active > .container {
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-bottom-left-radius: 3px;
            border-bottom-right-radius: 3px;
            border-top: 0;
        }

        .multiselect:hover > .container {
            border-top-color: rgba(0, 0, 0, 0.3);
        }

        .multiselect.active:hover > .container {
            border-color: rgba(0, 0, 0, 0.3);
        }

        .multiselect > .container > option {
            display: none;
            padding: 5px;
            cursor: pointer;
            transition: 0.2s;
            border-top: 1px solid transparent;
            border-bottom: 1px solid transparent;
        }

        .multiselect > .container > option.selected {
            background: rgb(122, 175, 233);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        .multiselect > .container > option:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #000;
        }

        .multiselect.active > .container > option {
             display: block;
            font-size: 12px;
        }

    </style>
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-12">
        <h4 class="page-title">Gestion du ramassage {{$ramassage->reference}}</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="/ramassage">Ramassage</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$ramassage->reference}}</li>

                    </ol>
                </nav>
            </div>
            <div class="row float-right" id="navbar-example3">
                @can('admin-superviseur')
                <a  class="btn btn-success text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormLivreur"><i class="fas fa-user"></i> <span class="quick-action"> Affecter</span></a>
                @endcan
                @if ($ramassage->statut === "En attente de ramassage")
                    @can('admin-superviseur-livreur')
                    <a  class="btn btn-success text-white m-r-5" data-toggle="modal" data-target="#modalRelance"><i class="fas fa-random"></i> <span class="quick-action">Valider </span></a>
                    @endcan
                @endif
                @if ($ramassage->statut === "Ramassé par le livreur")
                    @can('admin-superviseur')
                    <a  class="btn btn-success text-white m-r-5" data-toggle="modal" data-target="#modalRelance"><i class="fas fa-random"></i> <span class="quick-action">Valider </span></a>
                    @endcan
                @endif
                @can('client')
                    @if ($ramassage->statut === 'En attente de ramassage')
                        <a  class="btn btn-danger text-white"  data-toggle="modal" data-target="#modalAddRamassage"><i class="fa fa-plus-square"></i> Ajouter des commandes</a>
                    @endif
                @endcan
                <a  class="btn btn-success text-white m-r-5" href="{{ route('bon.infos',['id'=> $ramassage->bonlivraison()->first()->id ]) }}"><i class="fas fa-print"></i> <span class="quick-action">Bon de livraison </span></a>
            </div>
        </div>

      </div>

</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->


<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <div class="row">
        @if (session()->has('erreur'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{session()->get('erreur')}}
          </div>
        @endif
        @if (session()->has('CmdAddedSuccess'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{session()->get('CmdAddedSuccess')}}
          </div>
        @endif
        @if (session()->has('ramassage-validated'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Succés !</strong> Ramassage Validé </a>.
          </div>
        @endif
        @if (session()->has('ramassage-already-validated'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oups !</strong> Ramassage déjà Validé </a>.
          </div>
        @endif
    </div>    <!-- ============================================================== -->

    <div class="row">
      <div class="col-lg-12 col-xlg-3 col-md-5">
          <div class="card">
              <div class="card-body">
              <center class="m-t-30">
                  <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="table-responsive" style="border-top: solid;padding-top: 10px;">
                                <div class="row" style="text-align: left;border-bottom: solid;margin-bottom: 20px;">
                                    <div class="col-6">
                                        <h5><span style="font-weight: 900">Date de la demande de ramassage :</span> {{$ramassage->created_at}}</h5>
                                        <h5><span style="font-weight: 900">Ville de ramassage :</span> {{$ramassage->city}}</h5>
                                        <h5><span style="font-weight: 900">Adresse de ramassage :</span> {{$ramassage->adress}}</h5>
                                    </div>
                                    <div class="col-6">
                                        @if ($ramassage->livreurId != null)
                                        <h5><span style="font-weight: 900">Ramasseur :</span> {{App\User::where('id',$ramassage->livreurId)->first()->name}}</h5>
                                        @else
                                        <h5 style="color: red">Aucun ramasseur est affecté</h5>
                                        @endif
                                        <h5><span style="font-weight: 900">Statut :</span> {{$ramassage->statut}}</h5>
                                        <h5><span style="font-weight: 900">Satut modifié par :</span>
                                            @if($ramassage->statusUpdatedBy)
                                            {{App\User::where('id',$ramassage->statusUpdatedBy)->first()->name}}
                                            @else
                                            {{App\User::where('id',$ramassage->user_id)->first()->name}}
                                            @endif
                                        </h5>
                                    </div>

                                </div>
                              <h4>Les commandes liées à cette demande de ramassage</h4>
                              <table class="table table-striped" id="table-1">
                                <thead>
                                  <tr>
                                    <th class="text-center">
                                      #
                                    </th>
                                    <th>Commande</th>
                                    <th>Date de la commande</th>
                                    <th>Status</th>
                                    <th>ville de livraison</th>
                                    <th>Date du demande de ramassage</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @forelse ($commandes as $index => $commande)
                                  <tr>
                                    <td>
                                      {{$index}}
                                    </td>
                                    <td>
                                        <a href="/commandes/{{$commande->id}}">
                                            {{$commande->numero}}
                                        </a>
                                    </td>

                                    <td>{{$commande->created_at}}</td>
                                    <td>{{$commande->statut}}</td>
                                    <td>{{$commande->ville}}</td>
                                    <td>{{$ramassage->prevu_at}}</td>
                                  </tr>
                                  @empty

                                  @endforelse

                                </tbody>
                              </table>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
              </center>
              </div>

          </div>
      </div>

  </div>

    <div class="modal fade" id="modalRelance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Êtes-vous sûr de valider cette demande de ramassage ?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <h5>
                    Référence de la demande: {{$ramassage->reference}}
                </h5>
                <p class="proile-rating">Statut : {{$ramassage->statut}}</p>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Cliquez sur <b>Ok</b> pour confirmer ou <b>fermer</b> pour annuler la validation

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            <form method="GET" action="{{ route('ramassage.validate',['id'=> $ramassage->id]) }}">
                @csrf
                <button type="submit" class="btn btn-primary text-white m-r-5">Ok</button>
            </form>
            </div>
        </div>
        </div>
</div>


</div>



<div class="modal fade" id="modalSubscriptionFormLivreur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form  method="POST" action="{{route('ramassage.livreur',['id' => $ramassage->id])}}">
            @csrf
            @method('PATCH')
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Choisissez le livreur au quel vous voulez affecter cette demande de ramassage ?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <h5>
                Vendeur :  {{$ramassage->user()->first()->name}}
            </h5>
            <h5>
                Ville de Ramsasage: {{$ramassage->city}}
            </h5>
            <h6>
                Adresse de ramassage : {{$ramassage->adress}}
            </h6>
            <div class="form-group row">
                <label for="livreur" class="col-sm-4">Livreur :</label>
                <div class="col-sm-8">
                    <select name="livreurId" id="livreur" class="form-control form-control-line" value="{{ old('livreurId') }}">
                        <option value=""  selected >Choisissez le livreur</option>
                        @foreach ($livreurs as $livreur)
                            <option value="{{$livreur->id}}" class="rounded-circle" @if ($livreur->id == $ramassage->livreurId) selected @endif >
                                {{$livreur->name}}
                            </option>
                        @endforeach
                    </select>

                </div>
            </div>
        </div>
        <div class="modal-body">

          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            <button type="submit" class="btn btn-primary text-white m-r-5">Affecter</button>
        </div>
        </form>
      </div>
    </div>
  </div>

@endsection


<div class="container my-4">
    {{-- @can('ecom') --}}
    <div class="modal fade" id="modalAddRamassage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Ajouter des nouvelles commandes</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="POST" action="{{route('ramassage.update',['ramassage' => $ramassage])}}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" value="{{ old('commandes') }}" name="commandes" id="commandesToSet" required>
                                <div class="form-group">
                                    <label class="col-md-12">Commandes: </label>
                                    <div class="col-md-12">
                                        <div class="multiselect" style="width: 100%;" id="commandes" multiple="multiple" data-target="multi-0">
                                            <div class="title noselect">
                                                <span class="text">Selectionner les commandes</span>
                                                <span class="close-icon">&times;</span>
                                                <span class="expand-icon">&plus;</span>
                                            </div>
                                            <div class="container" style="width: 390px;">

                                                @foreach ($commandesToAdd as $commande)
                                                <option value="{{$commande->numero}}">
                                                    {{$commande->numero .'     (Ville: '. $commande->ville.')'}}
                                                </option>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button class="btn btn-danger">Ajouter les commandes</button>

                                    </div>
                                </div>
                            </form>
                            @if ($errors->any())
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>
                                        <strong>{{$error}}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                              </div>
                              @endif
                        </div>

                      </div>
                    </div>
    </div>
    {{-- @endcan --}}
</div>

@section('javascript')
    <script>
        // Created by @conmarap.

        Array.prototype.search = function(elem) {
            for(let i = 0; i < this.length; i++) {
                if(this[i] == elem) return i;
            }

            return -1;
        };

        let Multiselect = function(selector) {
            if(!$(selector)) {
                console.error("ERROR: Element %s does not exist.", selector);
                return;
            }

            this.selector = selector;
            this.selections = [];

            (function(that) {
                that.events();
            })(this);
        };

        Multiselect.prototype = {
            open: function(that) {
                let target = $(that).parent().attr("data-target");

                // If we are not keeping track of this one's entries, then
                // start doing so.
                if(!this.selections) {
                    this.selections = [ ];
                }

                $(this.selector + ".multiselect").toggleClass("active");
            },

            close: function() {
                $(this.selector + ".multiselect").removeClass("active");
            },

            events: function() {
                let that = this;

                $(document).on("click", that.selector + ".multiselect > .title", function(e) {
                    if(e.target.className.indexOf("close-icon") < 0) {
                        that.open();
                    }
                });

                $(document).on("click", that.selector + ".multiselect option", function(e) {
                    let selection = $(this).attr("value");
                    let target = $(this).parent().parent().attr("data-target");

                    let io = that.selections.search(selection);

                    if(io < 0) that.selections.push(selection);
                    else that.selections.splice(io, 1);

                    that.selectionStatus();
                    that.setSelectionsString();
                });

                $(document).on("click", that.selector + ".multiselect > .title > .close-icon", function(e) {
                    that.clearSelections();
                });

                $(document).click(function(e) {
                    if(e.target.className.indexOf("title") < 0) {
                        if(e.target.className.indexOf("text") < 0) {
                            if(e.target.className.indexOf("-icon") < 0) {
                                if(e.target.className.indexOf("selected") < 0 ||
                                e.target.localName != "option") {
                                    that.close();
                                }
                            }
                        }
                    }
                });
            },

            selectionStatus: function() {
                let obj = $(this.selector + ".multiselect");

                if(this.selections.length) obj.addClass("selection");
                else obj.removeClass("selection");
            },

            clearSelections: function() {
                this.selections = [];
                this.selectionStatus();
                this.setSelectionsString();
            },

            getSelections: function() {
                return this.selections;
            },

            setSelectionsString: function() {
                let selects = this.getSelectionsString().split(", ");
                $(this.selector + ".multiselect > .title").attr("title", selects);

                let opts = $(this.selector + ".multiselect option");

                if(selects.length > 6) {
                    let _selects = this.getSelectionsString().split(", ");
                    _selects = _selects.splice(0, 6);
                    $(this.selector + ".multiselect > .title > .text")
                        .text(_selects + " [...]");
                }
                else {
                    $(this.selector + ".multiselect > .title > .text")
                        .text(selects);
                }

                for(let i = 0; i < opts.length; i++) {
                    $(opts[i]).removeClass("selected");
                }

                for(let j = 0; j < selects.length; j++) {
                    let select = selects[j];

                    for(let i = 0; i < opts.length; i++) {
                        if($(opts[i]).attr("value") == select) {
                            $(opts[i]).addClass("selected");
                            break;
                        }
                    }
                }
            },

            getSelectionsString: function() {
                let commandeSelect = document.getElementById('commandesToSet');
                commandeSelect.value = this.selections;
                if(this.selections.length > 0)
                    return this.selections.join(", ");
                else return "Selectionner les commandes";
            },

            setSelections: function(arr) {
                if(!arr[0]) {
                    error("ERROR: This does not look like an array.");
                    return;
                }

                this.selections = arr;
                this.selectionStatus();
                this.setSelectionsString();
            },
        };

        $(document).ready(function() {
            let multi = new Multiselect("#commandes");
            console.log("🚀 ~ file: index.blade.php:583 ~ $ ~ multi:", multi)

        });

    </script>
@endsection

