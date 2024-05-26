<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    public function secteurs()
    {
        return $this->hasMany('App\Secteur');
    }
}
