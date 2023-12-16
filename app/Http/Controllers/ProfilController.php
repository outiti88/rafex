<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\User;
use App\Role;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class ProfilController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        $nouveau =  User::whereHas('roles', function($q){$q->whereIn('name', ['nouveau']);})->where('deleted_at',NULL)->count();

        $total =DB::table('commandes')->where('deleted_at',NULL)->where('user_id',Auth::user()->id )->count();
        $facture = DB::table('factures')->where('user_id',Auth::user()->id )->count();
        return view('profil')->with(['nouveau'=>$nouveau,'user'=> $user , 'total' => $total , 'facture' => $facture]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
       // dd($user);
      // dd($request);
       $user->name=$request->name;
       $user->email=$request->email;
       if ($request->hasfile('image')){
         $file = $request->file('image');
         $extension = $file->getClientOriginalExtension(); //getting image extension
         $filename = time() . '.' . $extension ;
         $file->move('uploads/userImages/',$filename);
         $user->image = '/uploads/userImages/'.$filename ;
     }
       $user->telephone=$request->telephone;
       $user->adresse=$request->adresse;
       $user->ville=$request->ville;

       $user->save();

       return redirect()->route('profil.index');
    }

}
