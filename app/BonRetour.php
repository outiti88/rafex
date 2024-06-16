<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonRetour extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function retours(){
        return $this->hasMany('App\Retour');
    }
}
