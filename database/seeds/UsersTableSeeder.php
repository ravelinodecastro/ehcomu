<?php

use Illuminate\Database\Seeder;
use App\User;
use Carbon\Carbon;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = ([
            [
                'first_name' => 'Ravelino',
                'last_name' => 'de Castro',
                'email' => 'ravelinodecastro@gmail.com',
                'username'=> 'ravelino',
                'type'=>'1',
                'gender'=>'1',
                'status'=>'1',
                'phone'=>'927562797',
                'password' => Hash::make('admin123'),
                'email_verified_at'=>Carbon:: now()-> format('Y-m-d H:i:s'),
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Carls',
                'last_name' => 'Delgado',
                'email' => 'carlagouveiadelgado0@gmail.com',
                'username'=> 'carla',
                'type'=>'1',
                'gender'=>'0',
                'status'=>'1',
                'phone'=>'932106555',
                'password' => Hash::make('admin123'),
                'email_verified_at'=>Carbon:: now()-> format('Y-m-d H:i:s'),
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
        ]);
        User::insert($users);
    }
}