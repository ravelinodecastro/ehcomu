<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['name','creator_id','status', 'type', 'avatar'];
    public function users(){
        return $this->belongsTo('App\User');
    }
}
