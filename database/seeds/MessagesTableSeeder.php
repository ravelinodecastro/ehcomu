<?php

use Illuminate\Database\Seeder;
use App\Models\Message;
use Carbon\Carbon;
class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $messages = ([
            [
                'body' => 'Essa mensagem foi criada dentro do seeder',
                'sender_id' => '1',
                'chat_id' => '1',
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
            [
                'body' => 'Essa mensagem também foi criada dentro do seeder',
                'sender_id' => '1',
                'chat_id' => '2',
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
        ]);
        Message::insert($messages);
    }
}