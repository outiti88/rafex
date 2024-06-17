@section('style')
    <style>
        .list-command{
            cursor: pointer;
        }
    </style>
@endsection

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#commandModal">
    Affecter les retours au livreur
  </button>

  <!-- Modal -->
  <div class="modal fade" id="commandModal" tabindex="-1" aria-labelledby="commandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-lg modal-dialog-scrollable modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="commandModalLabel">Affectation des retours au livreur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            {{-- alert ERROR--}}
            <div id="alertContainer" class="alert alert-danger d-none" role="alert">
                <span id="alertMessage">La commande n'existe pas</span>
            </div>
            {{-- alert SUCCESS--}}
            <div id="alertSuccessContainer" class="alert alert-success d-none" role="alert">
                <span id="alertMessageSuccess">Commandes ont été bien affectées</span>
            </div>
            {{-- loading Spinner--}}
            <div id="loadingSpinner" class="spinner-border text-primary d-none" role="status">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;"></div>
                  </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <h2>Scanner le QR code</h2>
                <input type="text" id="qrInput" class="form-control mb-3" placeholder="Entrer le numéro de commande">
                <h5>Liste des commandes prêtes à retourner</h5>
                <ul id="commandList" class="list-group" style="max-height: 300px; overflow-y: auto;">
                  <!-- La liste des commandes va être générée ici -->
                </ul>
              </div>
              <div class="col-md-6">
                <h2>Choisir le livreur</h2>
                <select name="livreur" id="livreurInputFormModal2" class="form-control form-control-line  mb-3" value="{{ old('livreur') }}">
                    <option value=""  selected >Choisissez le livreur</option>
                    @can('admin-personnel')
                        @foreach (App\User::whereHas('roles', function ($q) {
                            $q->whereIn('name', ['livreur']);
                        })->get()  as $livreur)
                            <option value="{{$livreur->id}}" class="rounded-circle">
                                {{$livreur->name}} - Ville : {{$livreur->ville}}
                            </option>
                        @endforeach
                    @endcan
                    @can('superviseur')
                        @foreach (App\User::where('ville',Auth::user()->ville)->whereHas('roles', function ($q) {
                            $q->whereIn('name', ['livreur']);
                        })->get()  as $livreur)
                            <option value="{{$livreur->id}}" class="rounded-circle">
                                {{$livreur->name}} - Ville : {{$livreur->ville}}
                            </option>
                        @endforeach
                    @endcan
                </select>
                <h5>Commandes sélectionnées</h5>
                <ul id="selectedCommands" class="list-group" style="max-height: 300px; overflow-y: auto;">
                  <!-- Les commandes sélectionnées vont être affichées ici -->
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button onclick="validateButton()" type="button" class="btn btn-primary">Valider</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

@section('javascript')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Déclaration de la variable commands en dehors de la fonction getCommandsFromBackend()
        let commands = [];
        var textarea = document.getElementById('qrInput');

        // Fonction pour récupérer les commandes depuis le backend
        function getCommandsFromBackend() {
        fetch('/retour/commandes/toaffect')
            .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la récupération des commandes');
            }
            return response.json();
            })
            .then(data => {
            // Stocker les commandes dans la variable commands
            commands = data;
            // Une fois les commandes récupérées, afficher les commandes dans la liste
            displayCommands(commands);
            })
            .catch(error => {
            console.error('Erreur:', error);
            });
        }

        // Fonction pour afficher les commandes dans la liste
        function displayCommands(commands) {
        let commandList = document.getElementById('commandList');
        commandList.innerHTML = '';
        commands.forEach(command => {
            let li = document.createElement('li');
            li.id = `notSelectedCommand_${command.numero}`;
            li.classList.add('list-group-item','list-command');
            li.textContent = `${command.numero} - ${command.ville} - ${command.vendeur}`;
            // Ajout d'un événement de clic pour sélectionner la commande
            li.addEventListener('click', () => selectCommand(command, li));
            commandList.appendChild(li);
        });
        }

        // Fonction pour sélectionner une commande
        function selectCommand(command, liElement) {
        let qrInput = document.getElementById('qrInput');
        console.log(command);
        // qrInput.value = command.numero;
        addSelectedCommand(command);
        // Supprimer la commande de la liste des commandes à gauche
        let commandList = document.getElementById('commandList');
        if (liElement && liElement.parentNode === commandList) {
            commandList.removeChild(liElement);
            } else {
                let liElement = document.getElementById(`notSelectedCommand_${command.numero}`);
                if(liElement){
                    commandList.removeChild(liElement);
                }
                else{
                    console.error('L\'élément à supprimer n\'a pas été trouvé dans la liste des commandes.');
                }
            }
        }

        // Fonction pour ajouter une commande sélectionnée
        function addSelectedCommand(command) {
        let selectedCommands = document.getElementById('selectedCommands');
        // Vérifier si la commande existe déjà dans la liste des commandes sélectionnées
        let existingCommand = document.getElementById(`selectedCommand_${command.numero}`);
        if (!existingCommand) {
            let li = document.createElement('li');
            li.id = `selectedCommand_${command.numero}`;
            li.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center','list-command');
            li.textContent = `${command.numero} - ${command.ville} - ${command.vendeur}`;
            // Ajout du bouton de désélection
            let removeButton = document.createElement('button');
            removeButton.textContent = '-';
            removeButton.classList.add('btn', 'btn-danger', 'btn-sm');
            removeButton.addEventListener('click', () => deselectCommand(command, li));
            li.appendChild(removeButton);
            selectedCommands.appendChild(li);
        } else {
            showCommandNotFoundAlert('La commande est déjà dans la liste');
        }
        }

        // Fonction pour désélectionner une commande
        function deselectCommand(command, liElement) {
        let selectedCommands = document.getElementById('selectedCommands');
        // Supprimer la commande de la liste des commandes sélectionnées
        selectedCommands.removeChild(liElement);
        // Réajouter la commande à la liste des commandes à gauche
        let commandList = document.getElementById('commandList');
        let li = document.createElement('li');
        li.id = `notSelectedCommand_${command.numero}`;
        li.classList.add('list-group-item', 'list-command');
        li.textContent = `${command.numero} - ${command.ville} - ${command.vendeur}`;
        // Ajout d'un événement de clic pour sélectionner la commande
        li.addEventListener('click', () => selectCommand(command, li));
        commandList.appendChild(li);
        }

        // Appeler la fonction pour récupérer les commandes depuis le backend au chargement de la page
        getCommandsFromBackend();

        textarea.addEventListener('paste', function(event) {
            // Récupérer le texte collé
            var pastedText = (event.clipboardData || window.clipboardData).getData('text');
            console.log(pastedText);
            console.log(commands);
            let selectedCommand = commands.find(c => c.numero === pastedText.trim());
            console.log(selectedCommand);
            if (selectedCommand) {
                selectCommand(selectedCommand, null); // null pour indiquer que l'élément de la liste n'est pas encore créé
            } else {
                showCommandNotFoundAlert('La commande n\'existe pas');
            }

            setTimeout(() => {
                console.log("Delayed for 1 second.");
                document.getElementById('qrInput').value = '';
            }, "50")
        });

        function validateButton() {
            // Afficher l'élément de chargement
            let loadingSpinner = document.getElementById('loadingSpinner');
            loadingSpinner.classList.remove('d-none');
            // Récupérer les IDs des commandes sélectionnées
            let selectedCommandIds = [];
            document.querySelectorAll('#selectedCommands li').forEach(function(li) {
                let commandId = li.id.replace('selectedCommand_', ''); // Supprimer le préfixe 'selectedCommand_' pour obtenir l'ID
                selectedCommandIds.push(commandId);
            });

            console.log(selectedCommandIds);

            // Récupérer l'ID du livreur choisi
            let livreurId = document.getElementById('livreurInputFormModal2').value;
            console.log(livreurId);
            console.log(selectedCommandIds.length > 0 && livreurId);

            if(selectedCommandIds.length > 0 && livreurId != null){
                // Effectuer la requête POST vers le contrôleur Laravel
                fetch('/retour/commandes/affect', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        selectedCommands: selectedCommandIds,
                        livreurId: livreurId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        // Read the response body and parse the JSON data
                        return response.json().then(data => {
                            throw new Error(data.error); // Throw the error with the message received from the backend
                        });
                    }
                    // Return the response to be used later
                    return response;
                })
                .then(response => response.json()) // Parse the response JSON
                .then(data => {
                    // Traiter la réponse si nécessaire
                    console.log(data);
                    // Vider la liste des commandes sélectionnées (à droite)
                    let selectedCommands = document.getElementById('selectedCommands');
                    selectedCommands.innerHTML = '';

                    // Remplir la liste des commandes à gauche avec les commandes récupérées
                    getCommandsFromBackend();
                    // Masquer l'élément de chargement
                    loadingSpinner.classList.add('d-none');
                    showCommandAffectedAlert('Commandes ont été bien affectées');
                })
                .catch(error => {
                    console.log('Erreur:', error);
                    showCommandNotFoundAlert(error);
                    // Masquer l'élément de chargement
                    loadingSpinner.classList.add('d-none');
                });
            }
            else{
                showCommandNotFoundAlert('Vous devrez selectionner un livreur et des commandes à affecter');
            }
        }


        function showCommandNotFoundAlert(message) {
            let alertContainer = document.getElementById('alertContainer');
            let alertMessage = document.getElementById('alertMessage');

            // Mettre à jour le message de l'alerte
            alertMessage.textContent = message;

            // Afficher l'alerte
            alertContainer.classList.remove('d-none');

            // Masquer l'alerte après 3 secondes
            setTimeout(function() {
                hideCommandNotFoundAlert();
            }, 5000);
            loadingSpinner.classList.add('d-none');
        }

        function hideCommandNotFoundAlert() {
            let alertContainer = document.getElementById('alertContainer');
            alertContainer.classList.add('d-none');
        }

        function showCommandAffectedAlert(message) {
            let alertSuccessContainer = document.getElementById('alertSuccessContainer');
            let alertMessage = document.getElementById('alertMessageSuccess');

            // Mettre à jour le message de l'alerte
            alertMessage.textContent = message;

            // Afficher l'alerte
            alertSuccessContainer.classList.remove('d-none');

            // Masquer l'alerte après 3 secondes
            setTimeout(function() {
                hideCommandAffectedAlert();
            }, 5000);
            loadingSpinner.classList.add('d-none');
        }

        function hideCommandAffectedAlert() {
            let alertSuccessContainer = document.getElementById('alertSuccessContainer');
            alertSuccessContainer.classList.add('d-none');
        }
    </script>
 @endsection
