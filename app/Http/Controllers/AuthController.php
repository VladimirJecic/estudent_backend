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
    $words = explode(' ', $trimmedName);
    $generatedEmail = strtolower(substr($words[0], 0, 1).substr($words[count($words)-1], 0, 1) ). $input['indexNum'] . '@student.fon.bg.ac.rs';

    // Check if a user with the same email exists
    if (User::where('indexNum', $input['indexNum'])->exists()) {
        return $this->sendError('User with the same indexNum already exists.', [], 409); // 409 Conflict status code
    }

    $input['password'] = bcrypt($input['password']);
    $input['email'] = $generatedEmail;
    $input['role'] = 'student';

    $user = User::create($input);

    $result['indexNum'] = $user->indexNum;
    $result['name'] = $user->name;
    $result['email'] = $user->email;
    $result['token'] = $user->createToken('MyApp')->accessToken;
    $result['role'] = $user->role;

    return $this->sendResponse($result, "\nUser Registered Successfully!");
}

    public function login(Request $request){
        if(Auth::attempt([
            'indexNum'=>$request->indexNum,
             'password'=>$request->password])){
                $user = Auth::user();
                $result['token'] = $user->createToken('MyApp')->accessToken;
                $result['name'] = $user->name;
                $result['indexNum'] = $user->indexNum;
                $result['email'] = $user->email;
                $result['role'] = $user->role;

                return $this->sendResponse($result, "\nUser Login Successful!");
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
