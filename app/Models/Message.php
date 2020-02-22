<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use App\User;
use App\Models\Chat;
use App\Models\Member;
class Message extends Model
{
    protected $fillable = ['body','status','sender_id','chat_id' ];
    public function chats(){
        return $this->belongsTo('App\Models\Chat');
    }
    public function toArray(){
        $user_id = null;
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user ?$user->id: '';
        } catch (JWTException  $e){
            
        } catch (TokenInvalidException $e){
          
        } catch (TokenExpiredException $e){
    
        }
        $chat = Chat::whereId($this->chat_id)->first();
        $members = Member::whereChatId($this->chat_id)->get();
        $isCreator = $chat->creator_id ==  $user_id;
        $isSender =  $this->sender_id == $user_id;
        return [
            'id' =>$this->id,
            'body' => $this->body,
            'isSender' =>   $isSender,
            'isCreator' => $isCreator,
            'isOnline' =>true,
            'status' => $this->status,
            'chat_id' => $this->chat_id,
            'sender_id' => $this->sender_id,
            'name' =>  $chat->name ||  Member::whereChatId($this->chat_id)->where('user_id', '!=',  $this->sender_id)->frist()->first_name,
            'avatar' => $chat->avatar || Member::whereChatId($this->chat_id)->where('user_id', '!=',  $this->sender_id)->frist()->avatar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
