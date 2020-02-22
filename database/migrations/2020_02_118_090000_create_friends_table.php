<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('requester_id')->unsigned()->nullable();
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('requested_id')->unsigned()->nullable();
            $table->foreign('requested_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', [2, 1, 0]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friends');
    }
}
