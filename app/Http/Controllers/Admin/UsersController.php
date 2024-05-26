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


        if(Gate::denies('edit-users')){
            return redirect(route('admin.users.index'));
        }

        $roles = Role::all();
        //dd($user->roles()->get()->pluck('name')->toArray());
        return view('admin.users.edit')->with([
            'nouveau'=>$nouveau,
            'user'=>$user,
            'roles'=>$roles,
            'villes'=>$villes
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

        if ($request->hasfile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); //getting image extension
            $filename = time() . '.' . $extension ;
            $file->move('uploads/userImages/',$filename);
            $user->image = '/uploads/userImages/'.$filename ;
        }

        $user->prix= (!empty($request->prix)) ? $request->prix : $user->prix;
        $user->name= (!empty($request->name)) ? $request->name : $user->name;
        $user->email= (!empty($request->email)) ? $request->email : $user->email;
        $user->ramassage_ville= (!empty($request->ramassage_ville)) ? $request->ramassage_ville : $user->ramassage_ville;
        $user->ville= (!empty($request->ville)) ? $request->ville : $user->ville;
        $user->description= (!empty($request->description)) ? $request->description : $user->description;
        $user->adresse= (!empty($request->adresse)) ? $request->adresse : $user->adresse;
        $user->adresse2= (!empty($request->adresse2)) ? $request->adresse2 : $user->adresse2;
        $user->storeName= (!empty($request->storeName)) ? $request->storeName : $user->storeName;
        $user->cin= (!empty($request->cin)) ? $request->cin : $user->cin;
        $user->rib= (!empty($request->rib)) ? $request->rib : $user->rib;
        // dd($request['role'] !== null);
        if($request['role'] !== null){
            $role = Role::select('id')->where('name',$request['role'])->first();
            $user->roles()->sync($role->id);
        }
        else{
        }
        $user->save();

        return back();
    }

    function removeEmptyValues($inputString) {
        // Split the input string by commas
        $values = explode(',', $inputString);

        // Remove empty elements
        $filteredValues = array_filter($values, function($value) {
            return !empty($value);
        });

        // Join the non-empty elements back into a string
        $resultString = implode(',', $filteredValues);

        return $resultString;
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
