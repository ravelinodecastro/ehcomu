<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use App\Models\Message;
    use App\Models\Chat;
    use App\User;
    use JWTAuth;
    use DB;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use App\Events\MessageEventPrivate;


    class MessageController extends Controller
    {
        public function getAll(){
            $messages = Message::paginate(10);
            return response()->json(['message' => "Operação realizada com sucesso.", 'messages' => $messages, 'success' => true]);
        }
        public function get($id, Request $request){
            $messages = Message::join('chats', 'chats.id', 'messages.chat_id')
            ->wherePermutId($id)
            ->paginate(10);
            $chat = Chat::whereId($id)->first();
            $isCreator = $chat->creator_id == $request->user()->id;
            $info = [
                'status' =>  $chat->status,
                'isCreator' =>  $isCreator,
                'name' =>  $chat->name ||  Member::whereChatId($this->chat_id)->where('user_id', '!=',  $this->sender_id)->frist()->first_name,
                'avatar' => $chat->avatar || Member::whereChatId($this->chat_id)->where('user_id', '!=',  $this->sender_id)->frist()->avatar,
            ];
            return response()->json(['message' => "Operação realizada com sucesso.", 'info'=>$info,'messages' => $messages, 'success' => true]);
        }
        public function conversations(Request $request){

            $conversations = Message::join('members', 'members.id', 'chat_id')
            ->where('members.user_id',$request->user()->id)
            ->orderBy('messages.created_at', 'DESC')
            ->paginate(10);
            return response()->json(['message' => "Operação realizada com sucesso.", 'conversations' => $conversations, 'success' => true]);
        }
        
        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'body' => 'required|string',
                'chat_id' => 'required',
            ]);
            if($validator->fails()){
                return response()->json([$validator->errors(), 'success' => false], 400);
            }
            $chat = Chat::findOrFail($request->chat_id); 
            $message = Message::create([
                'body' => $request->body,
                'status' => '0',
                'sender_id' => $request->user()->id,
                'chat_id' => $request->chat_id,
            ]);
            if($message)
            event(new MessageEventPrivate($request->user(), $chat, $message));
            return response()->json(['message' => 'Mensagem enviada com sucesso!', 'success' => true]); 
        }
        
    }