<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retour extends Model
{
    public function commande(){
        return $this->belongsTo('App\Commande');
    }

    public function statuts()
    {
        return $this->hasMany('App\Statut');
    }

    public function bonRetour(){
        return $this->belongsTo('App\BonRetour');
    }
}
