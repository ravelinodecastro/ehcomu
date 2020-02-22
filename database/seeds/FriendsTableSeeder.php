<?php

use Illuminate\Database\Seeder;
use App\Models\Firend;
use Carbon\Carbon;
class FirendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $friends = ([
            [
                'requester_id' => '1',
                'requested_id' => '2',
                'status' => '1',
                'created_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
                'updated_at' =>Carbon:: now()-> format('Y-m-d H:i:s'),
            ],
        ]);
        Firend::insert($friends);
    }
}