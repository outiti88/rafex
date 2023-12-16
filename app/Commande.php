<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commande extends Model
{

    use SoftDeletes;

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function ramassage(){
        return $this->belongsTo('App\Ramassage');
    }

    public function relances()
    {
        return $this->hasMany('App\Relance');
    }

    public function statuts()
    {
        return $this->hasMany('App\Statut');
    }

    public function produits(){
        return $this->BelongsToMany('App\Produit')->withPivot('qte');;
    }



}
