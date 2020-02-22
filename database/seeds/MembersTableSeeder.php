<?php

use Illuminate\Database\Seeder;
use App\Models\Member;
use Carbon\Carbon;
class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = ([
            [
                'user_id' => '1',
                'chat_id' => '1',
                'status' => '2',
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
            [
                'user_id' => '1',
                'chat_id' => '2',
                'status' => '2',
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
            [
                'user_id' => '2',
                'chat_id' => '1',
                'status' => '2',
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
            [
                'user_id' => '2',
                'chat_id' => '2',
                'status' => '2',
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
            
        ]);
        Member::insert($members);
    }
}