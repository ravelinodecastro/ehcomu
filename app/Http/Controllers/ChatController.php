<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use App\Models\Chat;
    use JWTAuth;
    use DB;
    use Tymon\JWTAuth\Exceptions\JWTException;


    class PermutController extends Controller
    {
        public function getAll(){
            $chats = Chat::paginate(10);
            return response()->json(['message' => "Operação realizada com sucesso.", 'chats' => $chats, 'success' => true]);
        }
        public function get(Request $request){
            $chats = Chat::whereId($request->user()->id)->paginate(10);
            return response()->json(['message' => "Operação realizada com sucesso.", 'chats' => $chats, 'success' => true]);
        }
        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'created_id' => 'required',

            ]);
            if($validator->fails()){
                return response()->json([$validator->errors(), 'success' => false], 400);
            }
            $chat = Chat::create([
                'status' => '0',
                'created_id' =>$request->created_id,
                'name' =>$request->name,
            ]);
            if($chat)
            return response()->json(['message' => 'Chat criado com sucesso!', 'success' => true]); 
        }
        
        public function update(Request $request){
            $chat = Chat::findOrFail($request->id);   
            $chat->status=$request->status;
            if ($chat->save())
            return response()->json(['message' => 'Chat actualizada com sucesso!', 'success' => true]);
            else 
            return response()->json(['message' => 'Falha ao realizar a operação!', 'success' => false]);
        }

    }