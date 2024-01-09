@extends('racine')

@section('title')
   Gestion des Ramassage
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
        <div class="col-5">
            <h4 class="page-title">Gestion des demandes de ramassages</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ramassage</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
        <div class="row float-right d-flex ">
            <div class="m-r-5" style="margin-right: 10px;">
                <a  class="btn btn-warning text-white"  data-toggle="modal" data-target="#modalStockSearch"><i class="fa fa-search"></i></a>
            </div>
            @can('client')
            <div class="m-r-5">
                <a  class="btn btn-danger text-white"  data-toggle="modal" data-target="#modalAddRamassage"><i class="fa fa-plus-square"></i> Ajouter</a>
            </div>
            @endcan
            <div class="container my-4">
                <div class="modal fade" id="modalStockSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header text-center">
                                      <h4 class="modal-title w-100 font-weight-bold">Filtrer</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body mx-3">
                                        <form class="form-horizontal form-material" method="GET" action="{{route('ramassage.filter')}}">
                                            @csrf
                                            @can('ramassage-commande')
                                                <div class="form-group row">
                                                    <label for="client" class="col-sm-4">Fournisseur :</label>
                                                    <div class="col-sm-8">
                                                        <select name="client" id="client" class="form-control form-control-line" value="{{ old('client') }}">
                                                            <option value="" disabled selected>Choisissez le fournisseur</option>
                                                            @foreach ($clients as $client)
                                                        <option value="{{$client->id}}" class="rounded-circle">
                                                            {{$client->name}}
                                                        </option>
                                                            @endforeach

                                                        </select>

                                                    </div>
                                                </div>
                                            @endcan


                                              <div class="form-group row">
                                                <label class="col-sm-4">Statut :</label>
                                                <div class="col-sm-8">
                                                    <select name="statut" class="form-control form-control-line" >
                                                        <option value="" disabled selected>Choisissez le Statut</option>
                                                        <option >En attente</option>
                                                        <option >Ramassaé par le livreur</option>
                                                        <option >Reçue</option>
                                                    </select>
                                                </div>
                                              </div>

                                            <div class="form-group row">
                                                <label for="libelle" class="col-sm-4">Ville :</label>
                                                <div class="col-sm-8">
                                                    <select name="city" class="form-control form-control-line" >
                                                        <option value="" disabled selected>Choisissez la Ville de ramassage</option>
                                                        <option >Rabat</option>
                                                        <option >Casablanca</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="modal-footer d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-warning"><i class="fa fa-search"></i> Rechercher</button>

                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                  </div>
                                </div>
                </div>
            </div>

        </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
           @if (session()->has('added'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
         Demande de ramassage envoyé avec la référence : <strong>{{session()->get('added')}} </strong>  </a>.
          </div>
        @endif
        <div class="collapse show" id="mycard-collapse" style="width : 100%;">
            <div class="card-body">
              <div class="row" style="display: flex;align-items: center;align-content: stretch;flex-wrap: wrap;justify-content: space-evenly">

                    <a  href="/ramassages/filter?statut=En attente" style="display:block ; margin: 0.5rem; font-size: 0.8em;padding: 1rem !important;color: white; cursor:pointer;margin-top:0.5rem" class="badge badge-secondary">
                      <span>En attente</span>
                    </a>
                    <a  href="/ramassages/filter?statut=Ramassé par le livreur" style="display:block ; margin: 0.5rem; font-size: 0.8em;padding: 1rem !important;color: white; cursor:pointer;margin-top:0.5rem" class="badge badge-success">
                      <span>Ramassé par le livreur</span>
                    </a>
                    <a  href="/ramassages/filter?statut=Reçue" style="display:block ; margin: 0.5rem; font-size: 0.8em;padding: 1rem !important;color: white; cursor:pointer;margin-top:0.5rem" class="badge badge-primary cielBadge">
                      <span>Reçue</span>
                    </a>

              </div>
            </div>
          </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Gérer vos demandes de ramassages</h4>
                    <h6 class="card-subtitle">Nombre total des demandes : <code>{{$total}} Demandes</code> .</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" style="font-size: 0.85em;">
                        <thead>
                            <tr>
                                @can('ramassage-commande')
                                <th scope="col">Client</th>
                                @endcan
                                <th scope="col">Réference</th>
                                <th scope="col">Nombre de colis</th>
                                <th scope="col">Téléphone</th>
                                <th scope="col">Ville</th>
                                <th scope="col">Date de la demande</th>
                                <th scope="col">Etat</th>
                                <th scope="col">Détail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ramassages as $index => $ramassage)
                                <tr>
                                    @can('ramassage-commande')
                                    <th scope="row">
                                        <a title="{{$users[$index]->name}}" class=" text-muted waves-effect waves-dark pro-pic"

                                                    @can('edit-users')
                                                        href="{{route('admin.users.edit',$users[$index]->id)}}"
                                                    @endcan


                                            >
                                            <img src="{{$users[$index]->image}}" alt="user" class="rounded-circle" width="31">
                                        </a>
                                    </th>
                                    @endcan
                                    <td>{{$ramassage->reference}}</td>
                                    <td>{{$ramassage->number}}</td>
                                    <td>{{$ramassage->phone}}</td>
                                    <td>{{$ramassage->city}}</td>
                                    <td>{{$ramassage->created_at}}</td>
                                    <td>
                                        <span style="color : white"       @switch($ramassage->statut)
                                            @case("En attente")
                                            class="badge-pill badge badge-secondary"
                                            @break
                                            @case("Ramassé par le livreur") class="badge badge-pill badge-success" @break
                                            @case("Reçue") class="badge badge-pill badge-primary cielBadge" @break
                                            @endswitch >
                                            {{$ramassage->statut}}
                                        </span>
                                    </td>
                                    <td style="font-size: 1.5em">
                                        <a style="color: #467a0f" href="/ramassage/{{$ramassage->id}}">
                                            <i class="ti-pencil"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align: center">Aucune demande de ramassage enregistrée!</td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{$ramassages ->appends($data)-> links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>





<div class="container my-4">
    {{-- @can('ecom') --}}
    <div class="modal fade" id="modalAddRamassage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Demander un ramassage</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="POST" action="{{route('ramassage.store')}}" enctype="multipart/form-data">
                                @csrf
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

                                                @foreach ($commandes as $commande)
                                                <option value="{{$commande->numero}}">
                                                    {{$commande->numero .'     (Client: '. $commande->nom.')'}}
                                                </option>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-12">Ville :</label>
                                    <div class="col-sm-12">
                                        <select name="city" class="form-control form-control-line" required>
                                            <option value="Rabat" >Rabat</option>
                                            <option value="Casablanca" >Casablanca</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Adresse :</label>
                                    <div class="col-md-12">
                                        <textarea  name="adress" rows="3" class="form-control form-control-line" required>{{ old('adress') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Téléphone :</label>
                                    <div class="col-md-12">
                                        <input type="text"  name="phone" value="{{ old('phone') }}" class="form-control form-control-line" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12" for="dateprevu">Date de ramassage :</label>
                                    <div class="col-md-12">
                                        <input id="dateprevu" type="date"  name="prevu_at" value="{{ old('prevu_at') }}" class="form-control form-control-line" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Description :</label>
                                    <div class="col-md-12">
                                        <textarea  name="description" rows="3" class="form-control form-control-line">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button class="btn btn-danger">Ajouter</button>

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

@endsection

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
