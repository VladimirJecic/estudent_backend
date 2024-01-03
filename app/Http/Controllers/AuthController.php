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
    $validator = Validator::make($request->all(), [
        'indexNum' => 'required',
        'name' => 'required',
        'password' => 'required',
        'confirmPassword' => 'required|same:password'
    ]);

    if ($validator->fails()) {
        return $this->sendError('Validation error.', $validator->errors());
    }

    $input = $request->all();

    // Create email
    $trimmedName = trim($input['name']);
    $formattedName = str_replace(' ', '.', $trimmedName);
    $generatedEmail = $formattedName . $input['indexNum'] . '@student.fon.bg.ac.rs';

    // Check if a user with the same email exists
    if (User::where('indexNum', $input['indexNum'])->exists()) {
        return $this->sendError('User with the same indexNum already exists.', [], 409); // 409 Conflict status code
    }

    $input['password'] = bcrypt($input['password']);
    $input['email'] = $generatedEmail;

    $user = User::create($input);

    $result['indexNum'] = $user->indexNum;
    $result['name'] = $user->name;
    $result['email'] = $user->email;
    $result['token'] = $user->createToken('MyApp')->accessToken;

    return $this->sendResponse($result, "\nUser Registered Successfully!");
}

    public function login(Request $request){
        if(Auth::attempt([
            'indexNum'=>$request->indexNum,
             'password'=>$request->password])){
                $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->accessToken;
                $success['name'] = $user->name;
                $success['indexNum'] = $user->indexNum;
                $success['email'] = $user->email;

                return $this->sendResponse($success, "\nUser Login Successful!");
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
