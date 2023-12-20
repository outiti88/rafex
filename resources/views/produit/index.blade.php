@extends('racine')

@section('title')
   Gestion de stock
@endsection

@section('content')


<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Gestion de stock</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Stock</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
        <div class="row float-right d-flex ">
            <div class="m-r-5" style="margin-right: 10px;">
                <a  class="btn btn-warning text-white"  data-toggle="modal" data-target="#modalStockSearch"><i class="fa fa-search"></i></a>
            </div>
            @can('ecom')
            <div class="m-r-5">
                <a  class="btn btn-danger text-white"  data-toggle="modal" data-target="#modalStockAdd"><i class="fa fa-plus-square"></i> Ajouter</a>
            </div>
            @endcan
            <div class="container my-4">
                <div class="modal fade" id="modalStockSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header text-center">
                                      <h4 class="modal-title w-100 font-weight-bold">Rechercher sur les Produits</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body mx-3">
                                        <form class="form-horizontal form-material" method="GET" action="{{route('stock.filter')}}">
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
                                                <label class="col-sm-4">Categorie :</label>
                                                <div class="col-sm-8">
                                                    <select name="categorie" class="form-control form-control-line" >
                                                        <option >Tous</option>
                                                        <option >Vêtements</option>
                                                        <option >Chaussures</option>
                                                        <option >Bijoux et accessoires</option>
                                                        <option >Produits Cosmétiques</option>
                                                        <option >Produits High Tech</option>
                                                        <option >Librairie</option>
                                                        <option >Maroquinerie</option>
                                                        <option >Végétaux</option>
                                                        <option >Autres</option>
                                                    </select>
                                                </div>
                                              </div>

                                              <div class="form-group row">
                                                <label for="libelle" class="col-sm-4">Nom du Produit :</label>
                                                <div class="col-sm-8">
                                                    <input  type="text" class="form-control form-control-line" name="libelle" id="libelle">

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
           @if (session()->has('corriger'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Stock Bien Corrigé !</strong> La nouvelle quantité est : {{session()->get('corriger')}}  </a>.
          </div>
        @endif
        @if (session()->has('noCorriger'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Erreur !</strong> Vous ne pouvez pas modifier la quantité des produits en stock  </a>.
          </div>
        @endif
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Gérer votre stock</h4>
                    <h6 class="card-subtitle">Nombre total de vos articles : <code>{{$total}} Articles</code> .</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" style="font-size: 0.85em;">
                        <thead>
                            <tr>
                                @can('ramassage-commande')
                                <th scope="col">Client</th>
                                @endcan
                                <th scope="col">Image</th>
                                <th scope="col">Reference</th>
                                <th scope="col">Nom du Produit</th>
                                <th scope="col">Categorie</th>
                                <th scope="col">Prix</th>
                                <th scope="col">Quantité</th>
                                <th scope="col">En commande</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @forelse ($produits as $index => $produit)
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

                            <th scope="row"> <a title="{{$produit->reference}}" class=" text-muted waves-effect waves-dark pro-pic">
                                    <img src="/uploads/produit/{{$produit->photo}}" alt="user" class="rounded-circle" width="31">
                                </a></th>
                            <td>{{$produit->reference}}</td>
                            <td>{{$produit->libelle}}</td>
                            <td>{{$produit->categorie}}</td>
                            <td>{{$produit->prix}} DH</td>

                            <td> <a  data-toggle="modal" data-target="#modalStockCorrection{{$produit->id}}" style="color: white; cursor: pointer;"  title="Corriger le stock"
                                @if ($stock[$index]->qte > 0)

                                    class="badge badge-pill badge-success">
                                    {{$stock[$index]->qte}}

                                @else
                                    @if ($stock[$index]->etat == 'Nouveau' )
                                        class="badge badge-pill badge-primary">
                                        Nouveau

                                    @else
                                        class="badge badge-pill badge-danger">
                                        RUPTURE

                                    @endif
                                @endif
                                    </a>
                                </td>

                                @can('manage-users')
                                <div class="container my-4">
                                    <div class="modal fade" id="modalStockCorrection{{$produit->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                      <div class="modal-content">
                                                        <div class="modal-header text-center">
                                                          <h4 class="modal-title w-100 font-weight-bold">Corriger le stock de {{$produit->libelle}}</h4>
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                        </div>
                                                        <div class="modal-body mx-3">
                                                            <form class="form-horizontal form-material" method="POST" action="{{route('stock.corriger',$produit->id)}}">
                                                                @csrf
                                                                <div class="form-group row">
                                                                    <label for="qte" class="col-sm-4">Quantité :</label>
                                                                    <div class="col-sm-8">
                                                                        <input  value="{{$stock[$index]->qte}}" type="number" class="form-control form-control-line" name="qte" id="qte" required>
                                                                    </div>
                                                                </div>



                                                                <div class="form-group">
                                                                    <div class="modal-footer d-flex justify-content-center">
                                                                        <button type="submit" class="btn btn-success">Corriger</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                      </div>
                                                    </div>
                                    </div>
                                </div>
                                @endcan


                                <td>
                                    @if ($stock[$index]->cmd > 0)
                                        <a href="{{route('reception.index')}}" style="color: white"
                                            class="badge badge-pill badge-info">
                                            {{$stock[$index]->cmd}}
                                        </a>
                                    @else
                                    <a href="{{route('reception.index')}}" style="color: white"
                                            class="badge badge-pill badge-warning">
                                            PAS DE RECEPTION
                                        </a>
                                    @endif

                                </td>


                           <td style="font-size: 1.5em">

                            <a style="color: #467a0f" href="/produit/{{$produit->id}}">
                                <i class="ti-pencil"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" style="text-align: center">Aucun produit enregistré!</td>
                        </tr>

                           @endforelse

                        </tbody>

                    </table>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{$produits ->appends($data)-> links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>





<div class="container my-4">
    @can('ecom')
    <div class="modal fade" id="modalStockAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Nouveau Produit</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="POST" action="{{route('produit.store')}}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label class="col-md-12">Nom du Produit :</label>
                                    <div class="col-md-12">
                                        <input  value="{{ old('libelle') }}" name="libelle" type="text" placeholder="Nom du Produit" class="form-control form-control-line" required>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="example-email" class="col-md-12">Prix (DH) :</label>
                                    <div class="col-md-12">
                                        <input  value="{{ old('prix') }}" type="number" class="form-control form-control-line" name="prix" >
                                    </div>
                                </div>

                                <div class="form-group" style="display:none">
                                    <label class="col-md-12">Description :</label>
                                    <div class="col-md-12">
                                        <textarea  name="description" rows="5" class="form-control form-control-line">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12">Categorie :</label>
                                    <div class="col-sm-12">
                                        <select name="categorie" class="form-control form-control-line" >
                                            <option >Vêtements</option>
                                            <option >Chaussures</option>
                                            <option >Bijoux et accessoires</option>
                                            <option >Produits Cosmétiques</option>
                                            <option >Produits High Tech</option>
                                            <option >Librairie</option>
                                            <option >Maroquinerie</option>
                                            <option >Végétaux</option>
                                            <option >Autres</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                      <input type="file" name="photo" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                      <label class="custom-file-label" for="inputGroupFile01">choisir une photo</label>
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
    @endcan
</div>


@endsection
