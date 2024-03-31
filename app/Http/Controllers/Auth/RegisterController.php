<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    protected function create(array $data, $request)
    {
        if(empty($data['image'])) $data['image']="https://tracking.Rafex.ma/assets/images/favicon.png";
        if ($request->hasfile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); //getting image extension
            $filename = time() . '.' . $extension ;
            $file->move('uploads/userImages/',$filename);
            $data['image'] = '/uploads/userImages/'.$filename ;
        }

            if(empty($data['description'])) $data['description']=" ";
            if(empty($data['adresse'])) $data['adresse']=" ";
            if(empty($data['adresse2'])) $data['adresse2']=" ";
            if(empty($data['ville'])) $data['ville']="Rabat";
            if(empty($data['rib'])) $data['rib']=" ";
            if(empty($data['storeName'])) $data['storeName']=" ";
            if(empty($data['cin'])) $data['cin']=" ";
            if(empty($data['ramassage_ville'])) $data['ramassage_ville']=" ";
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'description'=>$data['description'],
            'telephone'=>$data['telephone'],
            'adresse'=>$data['adresse'],
            'adresse2'=>$data['adresse2'],
            'ville'=>$data['ville'],
            'image'=>$data['image'],
            'rib'=>$data['rib'],
            'storeName'=>$data['storeName'],
            'cin'=>$data['cin'],
            'ramassage_ville'=>$data['ville']

        ]);

        if (!Gate::denies('edit-users')) {
            if(empty($data['roles'])){
                $role = Role::select('id')->where('name','nouveau')->first();
                $user->roles()->attach($role);
            }
            else {
                $user->roles()->sync($data['roles']);
            }
        }
        else{
            $role = Role::select('id')->where('name','nouveau')->first();
            $user->roles()->attach($role);
        }

        return $user;
    }

    protected function nouveau(){
        $villes = DB::table('villes')->orderBy('name')->get();

        return view('auth.nouveau' , ['villes' => $villes]);
    }

    protected function new($roleOfUser){
        $villes = DB::table('villes')->orderBy('name')->get();
        $nouveau =  0;
        $allRoles = array('admin','personnel','superviseur','stock','ramassage','livreur');
        if (in_array($roleOfUser, $allRoles) && !Gate::denies('edit-users')){
            return view('auth.register' , ['roleOfUser' => $roleOfUser,'villes' => $villes, 'nouveau' => $nouveau]);
        }
        else{
            throw new NotFoundHttpException('Page Not Found');
        }
    }
}
