<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Role;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        DB::table('role_user')->truncate();

        $adminRole = Role::where('name','admin')->first();
        $clientRole = Role::where('name','client')->first();
        $livreurRole = Role::where('name','livreur')->first();
        $personelRole = Role::where('name','personnel')->first();


        $client = User::create([
            'name'=>'Client User',
            'email' => 'client@quickoo.ma',
            'password' => Hash::make('12345678')
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@quickoo.ma',
            'password' => Hash::make('12345678')
        ]);

        $livreur = User::create([
            'name'=>'Livreur User',
            'email' => 'livreur@quickoo.ma',
            'password' => Hash::make('12345678')
        ]);

        $personnel = User::create([
            'name'=>'personnel User',
            'email' => 'personnel@quickoo.ma',
            'password' => Hash::make('12345678')
        ]);

        $admin->roles()->attach($adminRole);
        $client->roles()->attach($clientRole);
        $livreur->roles()->attach($livreurRole);
        $personnel->roles()->attach($personelRole);

    }
}
