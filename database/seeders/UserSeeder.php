<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

        $users = [
            [ "name"=>"ADMINISTRADOR" , "username" => "admin", "password"=>Hash::make("123456"), "id_rol"=>1],
        ];

        foreach ($users as $key => $value) {
            $user = new User();
            $user->id_rol = $value["id_rol"];
            $user->name = $value["name"];
            $user->username = $value["username"];
            $user->password = $value["password"];
            $user->estado_acceso = "A";

            $user->save();
        }
    }
}
