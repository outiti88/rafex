<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-users', function($user){
            return $user->hasAnyRoles(['admin','personnel']);
        });

        Gate::define('gestion-stock', function($user){
            return $user->hasAnyRoles(['admin','ecom','personnel']);
        });

        Gate::define('gestion-ramassage', function($user){
            return $user->hasAnyRoles(['admin','client','personnel','livreur','superviseur']);
        });

        Gate::define('ramassage-commande', function($user){
            return $user->hasAnyRoles(['admin','livreur','personnel','superviseur']);
        });

        Gate::define('client-admin', function($user){
            return $user->hasAnyRoles(['admin','client','ecom']);
        });

        Gate::define('fournisseur', function($user){
            return $user->hasAnyRoles(['ecom','client']);
        });

        Gate::define('gestion-commande', function($user){
            return $user->hasAnyRoles(['admin','client']);
        });

        Gate::define('edit-users', function($user){
            return $user->hasRole('admin');
        });

        Gate::define('delete-commande', function($user){
            return $user->hasAnyRoles(['admin','client','personnel','ecom']);
        });

        Gate::define('valide', function($user){
            return $user->hasAnyRoles(['admin','client','personnel','ecom','livreur','superviseur']);
        });

        Gate::define('nouveau', function($user){
            return $user->hasRole('nouveau');
        });

        Gate::define('delete-users', function($user){
            return $user->hasRole('admin');
        });


        Gate::define('admin', function($user){
            return $user->hasAnyRoles(['admin']);
        });
        Gate::define('livreur', function($user){
            return $user->hasAnyRoles(['livreur']);
        });
        Gate::define('client', function($user){
            return $user->hasRole('client');
        });
        Gate::define('ecom', function($user){
            return $user->hasRole('ecom');
        });
        Gate::define('superviseur', function($user){
            return $user->hasAnyRoles(['superviseur']);
        });
        Gate::define('personnel', function($user){
            return $user->hasAnyRoles(['personnel']);
        });
        Gate::define('admin-personnel', function($user){
            return $user->hasAnyRoles(['admin','personnel']);
        });
        Gate::define('livreur-admin', function($user){
            return $user->hasAnyRoles(['admin','livreur']);
        });
        Gate::define('livreur-superviseur', function($user){
            return $user->hasAnyRoles(['superviseur','livreur']);
        });
        Gate::define('admin-superviseur', function($user){
            return $user->hasAnyRoles(['superviseur','admin']);
        });
        Gate::define('admin-superviseur-livreur', function($user){
            return $user->hasAnyRoles(['superviseur','admin','livreur']);
        });
        Gate::define('admin-superviseur-personnel', function($user){
            return $user->hasAnyRoles(['superviseur','admin','personnel']);
        });

        Gate::define('client-admin-personnel-superviseur', function($user){
            return $user->hasAnyRoles(['admin','client','personnel','superviseur']);
        });
        Gate::define('client-admin-personnel-superviseur,livreur', function($user){
            return $user->hasAnyRoles(['admin','client','personnel','superviseur','livreur']);
        });

        //
    }
}
