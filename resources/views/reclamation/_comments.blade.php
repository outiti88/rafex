@cannot('livreur')

    @if ($from == 'show')
    <div class="row" style="margin-right: 0;margin-left: 0;">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="objet" class="col-sm-12">Filtrer par objet du ticket</label>
                <div class="col-sm-12">
                    <select id="myInput" class="form-control form-control-line" required>
                        <option value="all">Tous les objets</option>
                        <option value="Livraison">Livraison</option>
                        <option value="Retour"  >Retour</option>
                        <option value="Retour de fond"  >Retour de fond</option>
                        <option value="Modification de colis">Modification de colis</option>
                        <option value="Réclamation"  >Réclamation</option>
                        <option value="Autres"  >Autres</option>
                    </select>

                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="objet" class="col-sm-12">Filtrer par Satut du ticket</label>
                <div class="col-sm-12">
                    <select id="myInputStatut" class="form-control form-control-line" required>
                        <option value="all">Tous les statuts</option>
                        <option value="En attente">En attente</option>
                        <option value="Ticket fermé"  >Ticket fermé</option>
                    </select>

                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="table-responsive">
        <table class="table v-middle">
            <thead>
                <tr class="bg-light">
                    <th class="border-top-0">Numéro de ticket</th>
                    @if ($from == 'reclamation')
                        @can('manage-users')
                            <th class="border-top-0">Nom du Fournisseur</th>
                        @endcan
                        <th class="border-top-0">Numéro de la commmande</th>
                    @endif
                    <th class="border-top-0">Objet</th>
                    <th class="border-top-0">Statut</th>
                    <th class="border-top-0">Date de création</th>
                    <th class="border-top-0">Détails</th>
                </tr>
            </thead>
            <tbody id="myTable">


                @forelse ($reclamations as $index => $reclamation)
                    <tr @if ( (session()->has('commentAdded') && $reclamation->id == session()->get('commentAdded'))
                        ||
                        (session()->has('traiter') && $reclamation->id == session()->get('traiter')) ||
                        (session()->has('ajouter') && $reclamation->id == session()->get('ajouter')) )
                        style="background-color: #649c1270;"@endif
                        >
                        <td>
                            <h5 class="m-b-0">
                                <div class="modal fade" id="modalValidateTicket{{$reclamation->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Voulez vous fermer le ticket {{$reclamation->number}}?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="/reclamation/{{$reclamation->id}}"  class="btn btn-primary text-white m-r-5">Valider</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                {{$reclamation->number}}
                            </h5>
                        </td>
                        @if ($from != 'show')
                            @can('manage-users')
                            <td style="text-align: left;
                            padding-left: 24px;">
                                <a title="Tel: {{$fournisseurs[$index]->telephone}}" @can('edit-users')
                                    href="{{route('admin.users.edit',$fournisseurs[$index]->id)}}" @endcan>
                                    <img src="{{$fournisseurs[$index]->image}}" alt="user" class="rounded-circle"
                                        width="31" />
                                    <h5 style="color: #666666;display: inline;" class="m-b-0 font-16">
                                        {{$fournisseurs[$index]->name}}</h5>
                                </a>
                            </td>
                            @endcan
                            <td>
                                <a title="Voir la commande" href="/commandes/{{$commandes[$index]->id}}">
                                    <h5 style="color: #666666;" class="m-b-0 font-16">{{$commandes[$index]->numero}}
                                    </h5>
                                </a>

                            </td>
                        @endif
                        <td>
                            <h5 class="m-b-0">
                                {{$reclamation->objet}}
                            </h5>
                        </td>
                        <td>
                            @if ($reclamation->etat == 0)
                            <a title="Fermer le ticket"
                            @can('manage-users')data-toggle="modal" data-target="#modalValidateTicket{{$reclamation->id}}" @endcan>
                                <span class="badge badge-pill badge-info" style="color: white; cursor: pointer;">
                                    <h5 class="m-b-0">
                                        En attente
                                    </h5>
                                </span>
                            </a>
                            @else
                            <span class="badge badge-pill badge-success">
                                <h5 class="m-b-0">
                                    Ticket fermé
                                </h5>
                            </span>
                            @endif
                        </td>
                        <td>
                            <h5 class="m-b-0">
                                {{$reclamation->created_at}}</h5>

                        </td>
                        <td><a class="btn btn-warning text-white" data-toggle="modal"
                                data-target="#modalComment{{$reclamation->id}}"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    <div class="modal fade" id="modalComment{{$reclamation->id}}" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title w-100 font-weight-bold">Objet : {{$reclamation->objet}}</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <h4 class="modal-title w-100 font-weight-bold text-center">Numéro : {{$reclamation->number}}</h4>
                                <div class="modal-body">
                                    @if ($reclamation->objet == 'Modification de colis' && isset($oldCommandes[$reclamation->id]))
                                    <div class="row">
                                        @if ($from == 'show')
                                        @include('reclamation._old')
                                        @else
                                        @include('reclamation._old', ['commande' => $commandes[$index]])
                                        @endif
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col">
                                            @foreach ($commentTickets[$reclamation->id] as $index => $commentTicket)
                                            <div class="d-flex flex-start">
                                                @if ($index == 0)
                                                <img class="rounded-circle shadow-1-strong me-3"
                                                    src="{{$commentTicket->user()->first()->image}}" alt="avatar"
                                                    width="45" height="45" />
                                                <div class="flex-grow-1 flex-shrink-1">

                                                    <div style="margin-left: 15px">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-1" style="font-weight: bold; color: black;" >
                                                                {{$commentTicket->user()->first()->name}} <span
                                                                    class="small">-
                                                                    {{\Carbon\Carbon::parse($commentTicket->created_at)->diffForHumans()}}</span>
                                                            </p>
                                                        </div>
                                                        <p class="small mb-0">
                                                            {{$commentTicket->comment}}
                                                        </p>
                                                        @if ($commentTicket->image)
                                                        <a href="/uploads/ticket/{{$commentTicket->image}}"
                                                            style=" text-decoration: none;" target="_Blank">
                                                            <img src="/uploads/ticket/{{$commentTicket->image}}"
                                                                width="200" />
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>

                                                @else
                                                <div class="d-flex flex-start mt-4" style="margin-left: 20px">
                                                    <a class="me-3" href="#">
                                                        <img class="rounded-circle shadow-1-strong"
                                                            src="{{$commentTicket->user()->first()->image}}"
                                                            alt="avatar" width="45" height="45" />
                                                    </a>
                                                    <div class="flex-grow-1 flex-shrink-1">
                                                        <div style="margin-left: 15px">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <p class="mb-1" style="font-weight: bold;">
                                                                    {{$commentTicket->user()->first()->name}} <span
                                                                        class="small">-
                                                                        {{\Carbon\Carbon::parse($commentTicket->created_at)->diffForHumans()}}</span>
                                                                </p>
                                                            </div>
                                                            <p class="small mb-0">
                                                                {{$commentTicket->comment}}
                                                            </p>
                                                            @if ($commentTicket->image)
                                                            <a href="/uploads/ticket/{{$commentTicket->image}}"
                                                                style=" text-decoration: none;" target="_Blank">
                                                                <img src="/uploads/ticket/{{$commentTicket->image}}"
                                                                    width="200" />
                                                            </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="divider"
                                                style="border-top: 1px solid #ccc; margin-top: 20px;"></div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if ($reclamation->etat == 0)
                                    <form method="POST" action="{{route('reclamation.addComment')}}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" value="{{$reclamation->id}}" name="reclamation" />
                                        <div class="d-flex flex-start w-100" style="margin-top: 40px;">
                                            <img class="rounded-circle shadow-1-strong me-3"
                                                src="{{Auth::user()->image}}" alt="avatar" width="40" height="40" />
                                            <div class="form-outline w-100">
                                                <textarea class="form-control" name="description" id="description"
                                                    rows="4" style="background: #fff;"
                                                    placeholder="Ecrire un commentaire" required></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-top: 20px;">
                                            <div class="custom-file"
                                                style="display: flex; justify-content: center;">
                                                <input type="file" accept="image/*" name="image" id="file{{$reclamation->id}}"
                                                    onchange="loadFile2(event, {{$reclamation->id}})" style="display: none">
                                                <label class="alert alert-dismissible alert-success"
                                                    style="margin: 0;padding: 12px 20px;" for="file{{$reclamation->id}}"
                                                    style="cursor: pointer;">Upload Image</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display: flex; justify-content: center;">
                                            <img id="output{{$reclamation->id}}" width="200" />
                                        </div>
                                        <div class="form-group">
                                            <div class="modal-footer d-flex justify-content-center">
                                                <button type="submit" class="btn btn-warning"> Envoyer un commentaire</button>
                                                @can('manage-users')
                                                    <a style="color: white;" href="/reclamation/{{$reclamation->id}}" class="btn btn-warning"> Fermer le ticket</a>
                                                @endcannot
                                            </div>
                                        </div>
                                    </form>
                                    @else
                                    <div class="alert alert-dismissible alert-success"
                                        style="text-align: center; margin-top: 20px;">
                                        <strong>Ticket fermé </strong> <span style="font-size: 10px;">{{$reclamation->updated_at}}</span>
                                    </div>
                                    @endif

                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center">Aucun ticket enregistrée!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($from == 'reclamation')
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{$reclamations ->appends($data)-> links()}}
            </div>
        </div>
        @endif
    </div>




@endcannot
