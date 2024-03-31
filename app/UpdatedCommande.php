<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpdatedCommande extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function relamation(){
        return $this->belongsTo('App\Reclamation');
    }
}
