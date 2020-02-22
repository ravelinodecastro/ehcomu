<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('body');
            $table->enum('status', [ 1, 0]);
            $table->bigInteger('sender_id')->unsigned()->nullable();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('chat_id')->unsigned()->nullable();
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
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
        Schema::dropIfExists('messages');
    }
}
