<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Role;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo =  RouteServiceProvider::USER;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        //$this->middleware('auth');
    }*/
    public function __construct()
    {

    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'ville'=> ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        if(empty($data['image'])) $data['image']="https://tracking.Rafex.ma/assets/images/favicon.png";
            if(empty($data['description'])) $data['description']=" ";
            if(empty($data['adresse'])) $data['adresse']=" ";
            if(empty($data['ville'])) $data['ville']="Rabat";
            if(empty($data['rib'])) $data['rib']=" ";
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'description'=>$data['description'],
            'telephone'=>$data['telephone'],
            'adresse'=>$data['adresse'],
            'ville'=>$data['ville'],
            'image'=>$data['image'],
            'rib'=>$data['rib'],
            'storeName'=>$data['storeName'],
            'cin'=>$data['cin'],

        ]);


        if(empty($data['roles'])){
        $role = Role::select('id')->where('name','nouveau')->first();
        $user->roles()->attach($role);
        }
        else {
            $user->roles()->sync($data['roles']);
        }
        return $user;
    }

    protected function nouveau(){
        $villes = DB::table('villes')->orderBy('name')->get();

        return view('auth.nouveau' , ['villes' => $villes]);
    }
}
