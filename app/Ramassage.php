<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ramassage extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function commandes(){
        return $this->hasMany('App\Commande');
    }

    public function bonlivraison(){
        return $this->hasOne('App\BonLivraison');
    }


}
