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
</div>
<div class="form-group row">
    <label for="cin" class="col-md-2 col-form-label text-md-right">{{ __('N°: CIN') }}</label>

    <div class="col-md-10">
        <input id="cin" type="text" class="form-control" name="cin" value="{{ old('cin') }}"  required  >
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
