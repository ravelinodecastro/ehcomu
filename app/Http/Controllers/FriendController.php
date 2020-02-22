<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use App\Models\Friend;
    use JWTAuth;
    use DB;
    use Tymon\JWTAuth\Exceptions\JWTException;


    class FriendController extends Controller
    {
        public function get(){
            $friends = Friend::orderBy('state')->get();
            return response()->json(['message' => "Operação realizada com sucesso.", 'friends' => $friends, 'success' => true]);
        }
        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'requested_id' => 'required',
            ]);
            if($validator->fails()){
                return response()->json([$validator->errors(), 'success' => false], 400);
            }
            $friend = Friend::create([
                'requested_id' => $request->requested_id,
                'requester_id' => $request->user()->id,
            ]);
            if($friend)
            return response()->json(['message' => 'Pedido de amizade feito com sucesso!', 'success' => true]); 
        }
        
        public function update(Request $request){
            $friend = Friend::findOrFail($request->id);   
            $friend->status=$request->status;
            if ($status->save())
            return response()->json(['message' => 'Amizade actualizada com sucesso!', 'success' => true]);
            else 
            return response()->json(['message' => 'Falha ao realizar a operação!', 'success' => false]);
        }

    }