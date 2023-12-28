<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonLivraison extends Model
{
    /**
     * Get the post that owns the comment.
     */

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function ramassage()
    {
        return $this->belongsTo('App\Ramassage');
    }
}
