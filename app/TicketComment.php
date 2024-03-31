<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function reclamation(){
        return $this->belongsTo('App\Reclamation');
    }
}
