<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    public function ville(){
        return $this->belongsTo('App\Ville');
    }
}
