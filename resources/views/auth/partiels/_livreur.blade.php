<div class="form-group row">
    <div class="col-md-6">
        <div class="row">
            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nom Complet') }}</label>
            <div class="col-md-8">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <label for="cin" class="col-md-4 col-form-label text-md-right">{{ __('N°: CIN') }}</label>

            <div class="col-md-8">
                <input id="cin" type="text" class="form-control" name="cin" value="{{ old('cin') }}"  required  >
            </div>
        </div>
    </div>
</div>

<div class="form-group row">

    <div class="col-md-6">
        <div class="row">
            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

            <div class="col-md-8">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <label for="ville" class="col-md-4 col-form-label text-md-right">{{ __('Ville') }}</label>

            <div class="col-md-8">
                <select  value="{{ old('ville') }}"  name="ville" class="form-control form-control-line" id="ville" required>
                            <option selected="" disabled="">Choisissez la ville</option>
                            @foreach ($villes as $ville)
                            <option value="{{$ville->name}}" class="rounded-circle">
                                {{$ville->name}}
                            </option>
                            @endforeach
                </select>
                @error('ville')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>
        </div>
    </div>

</div>

<div class="form-group row">
    <div class="col-md-6">
        <div class="row">
            <label for="telephone" class="col-md-4 col-form-label text-md-right">{{ __('Téléphone') }}</label>

            <div class="col-md-8">
                <input id="telephone" type="text" class="form-control" name="telephone" value="{{ old('telephone') }}"  required  >
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <label class="col-md-4 col-form-label text-md-right" for="inputGroupFile01">Photo de profil</label>
            <div class="col-md-8 custom-file">
                <input type="file" name="image" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                <label class="custom-file-label" for="inputGroupFile01">Télécharger</label>
              </div>
        </div>
    </div>

</div>
{{-- <div class="form-group row">
    <div class="col-md-12">
        <div class="row">
            <label for="type" class="ramassage col-md-4 col-form-label text-md-right" style="display: none">Ramassage : </label>
            <div class="ramassage col-md-8 p-t-10 justify-content-around">
                <div class="form-check">
                    <input type="checkbox"  id="ramassageVille" class="custom-switch-input">
                    <label for="ramassageVille">Ce Livreur, peut-il effectuer les ramassages ?</label>
                </div>
                <div class="form-check">
                    <select  value="{{ old('ramassage_ville') }}"  name="ramassage_ville" class="form-control form-control-line" id="ramassage_ville" style="display: none">
                        <option value="" selected="" disabled="">Choisissez la ville</option>
                        <option value="Casablanca" class="rounded-circle">
                            Casablanca
                        </option>
                        <option value="Rabat" class="rounded-circle">
                            Rabat
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div> --}}
