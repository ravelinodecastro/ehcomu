<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['user_id', 'chat_id', 'status' ];
    public function users(){
        return $this->belongsTo('App\User');
    }
    public function chats(){
        return $this->belongsTo('App\Models\Chat');
    }
}
