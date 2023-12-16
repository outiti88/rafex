<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use SoftDeletes;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','description','telephone','adresse','image','ville','statut','rib','prix','storeName','cin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(){
        return $this->BelongsToMany('App\Role');
    }

    public function hasAnyRoles($roles){
        if($this->roles()->whereIn('name',$roles)->first()) {return true;}
        return false;
    }

    public function hasRole($role){
        if($this->roles()->where('name',$role)->first()) {return true;}
        return false;
    }

    public function commandes(){
        return $this->hasMany('App\Commande');
    }

    public function ramassages(){
        return $this->hasMany('App\Ramassage');
    }

    public function produits(){
        return $this->hasMany('App\Produit');
    }

    public function mouvements(){
        return $this->hasMany('App\Mouvement');
    }

    public function receptions()
    {
        return $this->hasMany('App\Reception');
    }

    public function caisses(){
        return $this->hasMany('App\Caisse');
    }


    public function bonLivraisons()
    {
        return $this->hasMany('App\BonLivraison');
    }

    public function factures()
    {
        return $this->hasMany('App\Facture');
    }

    public function statuts()
    {
        return $this->hasMany('App\Statut');
    }

    public function relances()
    {
        return $this->hasMany('App\Relance');
    }

    public static function boot(){
        parent::boot();

        static::deleting(function(User $user){
            $user->commandes()->delete();
        });

    }
}
