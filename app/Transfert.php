<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function commandes(){
        return $this->hasMany('App\Commande');
    }
}
