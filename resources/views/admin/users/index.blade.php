
@extends('racine')

@section('title')
   Gestion des Utilisateurs
@endsection



@section('style')
    <style>
        .page-link {
            color: #467a0f !important;
        }
        .page-item.active .page-link {

            background-color: #467a0f !important;
            border-color: #467a0f !important;
            color: #fff !important;
        }
    </style>
@endsection


@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Gestion des Utilisateurs</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Cavallo</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Utilisateurs</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
            <div class="text-right upgrade-btn">
            <a  class="btn btn-danger text-white" href="{{route('register')}}"><i class="fa fa-plus-square"></i> Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    @if (session()->has('register'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oupss !</strong> l'utilisateur : {{session()->get('register')}} à été bien enregister et mail envoyé </a>.
          </div>
        @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Utilisateurs Cavallo') }}</div>
                <div class="card-header">Total des Utilisateurs: {{ $total }} Utilisateur</div>
                <input class="form-control" id="myInput" type="text" placeholder="Rechercher...">
                <div class="card-body" >
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                @can('edit-users')
                                <th scope="col">Action</th>
                                @endcan
                                <th scope="col">Nom & Prénom</th>
                                <th scope="col">Rôles</th>
                                <th scope="col">Date</th>
                                <th scope="col">Email</th>
                                <th scope="col">Ville</th>
                              </tr>
                            </thead>

                            <tbody id="myTable">
                                @foreach ($users as $user)
                              <tr style="padding: 0; margin:0">
                                <th  style="padding: 0.5rem; margin:0" scope="row"><a><img src="{{$user->image}}" alt="user" class="rounded-circle
                                    @if($user->statut)
                                    vip
                                @endif
                                    " width="31"></a></th>
                                    @can('edit-users')
                                <td  style="padding: 0.5rem; margin:0; display:flex  ;  width: auto;">
                                    <a href="{{route('admin.users.edit',$user->id)}}">
                                       <button style="padding: 0.5rem;" class="btn btn-primary float-lef"><i class="mdi mdi-account-edit"></i></button>
                                   </a>
                                <a style="padding: 0.5rem;" class="btn btn-danger text-white m-r-5" data-toggle="modal" data-target="#FormDelete{{$user->id}}"><i class="fas fa-trash-alt"></i></a>

                                <div class="modal fade" id="FormDelete{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">Êtes-vous sûr de vouloir supprimer cet utilisateur ?</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                            <h5>
                                                Nom Complet: {{$user->name}}
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                Cliquez sur <b>Ok</b> pour confirmer ou <b>fermer</b> pour annuler la suppression

                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>
                                          </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                                            <form action="{{route('admin.users.destroy',$user->id)}}" method="POST" class="float-left">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit" class="btn btn-danger text-white m-r-5">Ok</button>
                                            </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>


                               </td>
                                @endcan
                                <td   style="padding: 0.5rem; margin:0">{{$user->name}}</td>
                                <td  style="padding: 0.5rem; margin:0">{{ implode(', ' , $user->roles()->get()->pluck('name')->toArray() )}}</td>
                                <td  style="padding: 0.5rem; margin:0">{{$user->created_at}}</td>

                                <td  style="padding: 0.5rem; margin:0">{{$user->email}}</td>
                                <td  style="padding: 0.5rem; margin:0">{{$user->ville}}</td>


                                </tr>
                              @endforeach

                            </tbody>
                          </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>




@endsection

@section('javascript')
    @if ($errors->any())
        <script type="text/javascript">
            $(window).on('load',function(){
                $('#modalSubscriptionForm').modal('show');
            });
        </script>
    @endif

    <script>
        $(document).ready(function(){
          $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });
        });
        </script>
@endsection
