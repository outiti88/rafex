<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nouveau =  User::whereHas('roles', function($q){$q->whereIn('name', ['nouveau']);})->where('deleted_at',NULL)->count();

        $users = User::all();
        $total = User::count();
        //dd($users);
        return view('admin.users.index')->with(['users'=>$users,
                                'total' => $total,
                                'nouveau'=>$nouveau]);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $nouveau =  User::whereHas('roles', function($q){$q->whereIn('name', ['nouveau']);})->where('deleted_at',NULL)->count();
        $villes= DB::table('villes')->orderBy('name')->get();
        $userVilles = explode(",", $user->ville);
        if(in_array(",", $userVilles)){
            unset($userVilles[count($userVilles)-1]);
        }

        if(Gate::denies('edit-users')){
            return redirect(route('admin.users.index'));
        }

        $roles = Role::all();
        //dd($user->roles()->get()->pluck('name')->toArray());
        return view('admin.users.edit')->with([
            'nouveau'=>$nouveau,
            'user'=>$user,
            'roles'=>$roles,
            'villes'=>$villes,
            'userVilles' => $userVilles
        ]);
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

        if(Gate::denies('edit-users')){
            return redirect(route('admin.users.index'));
        }
       //dd($request->roles[0] ==3);
       $user->roles()->sync($request->roles);
       $user->prix=$request->prix;
       $user->image=$request->image;
       $user->name=$request->name;
       $user->email=$request->email;
       if($request->roles[0] ==3){
        $user->ville= "";
        foreach ($request->ville as $index => $ville){
         $user->ville .= $ville .',';
        }
       }
       else{
            $user->ville= "";
        foreach ($request->ville as $index => $ville){
            $user->ville .= $ville;
        }
       }


       $user->description = $request->description;
       $user->storeName = $request->storeName;
       $user->cin = $request->cin;
       $user->statut = $request->statut;
       $user->rib = $request->rib;
       $user->save();

       return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(Gate::denies('delete-users')){
            return redirect(route('admin.users.index'));
        }

        $user->roles()->detach();
        $user->delete();

        return redirect()->route('admin.users.index');

    }
}
