<?php

namespace App\Http\Controllers;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends BaseController
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'role'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation error.',$validator->errors());
        }

        $input = $request->all();
        $input['password']=bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, "\nUser Registered Successfully");
    }

    public function login(Request $request){
        if(Auth::attempt([
            'indexNum'=>$request->indexNum,
             'password'=>$request->password])){
                $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->accessToken;
                $success['name'] = $user->name;

                return $this->sendResponse($success, "\nUser Login Successfull");
             }else{
                return $this->sendError('Unauthorised.',['error'=>'Unathorized!']);
             }
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Success: You have logged out and the token was deleted'
        ];
    }
}
