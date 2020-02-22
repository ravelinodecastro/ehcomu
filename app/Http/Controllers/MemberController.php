<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use App\Models\Member;
    use JWTAuth;
    use DB;
    use Tymon\JWTAuth\Exceptions\JWTException;


    class MemberController extends Controller
    {
        public function get($id){
            $members = Member::whereChatId($id)->get();
            return response()->json(['message' => "Operação realizada com sucesso.", 'members' => $members, 'success' => true]);
        }
        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'institution' => 'required|string|max:255|unique:institutions',
            ]);
            if($validator->fails()){
                return response()->json([$validator->errors(), 'success' => false], 400);
            }
            $member = Member::create([
                'chat_id' => $request->chat_id,
                'user_id' => $request->user_id,
            ]);
            if($member)
            return response()->json(['message' => 'Integrante de um chat cadastrado com sucesso!', 'success' => true]); 
        }
        
        public function update(Request $request){
            $member = Member::findOrFail($request->id);   
            $member->status=$request->status;
            if ($member->save())
            return response()->json(['message' => 'Integrante actualizado com sucesso!', 'success' => true]);
            else 
            return response()->json(['message' => 'Falha ao realizar a operação!', 'success' => false]);
        }

        
    }