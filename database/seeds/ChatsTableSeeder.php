<?php

use Illuminate\Database\Seeder;
use App\Models\Chat;
use Carbon\Carbon;
class ChatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chats_ground = ([
            [
                'name' => 'Angola para Cristo',
                'creator_id' => '1',
                'type' => '0',
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
        ]);
        $chats_single = ([
            [
                'creator_id' => '1',
                'type' => '1',
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
        ]);
        Chat::insert($chats_ground);
        Chat::insert($chats_single);
    }
}