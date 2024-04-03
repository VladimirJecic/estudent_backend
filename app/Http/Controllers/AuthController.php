<?php

namespace App\Http\Controllers;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
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
            return $this->sendError('Validation error.', $validator->errors(),400);
        }

        $input = $request->all();

        // Create email
        $trimmedName = trim($input['name']);
        $words = explode(' ', $trimmedName);
        $generatedEmail = strtolower(substr($words[0], 0, 1).substr($words[count($words)-1], 0, 1) ). str_replace('/', '', $input['indexNum']) . '@student.fon.bg.ac.rs';

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

     /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Login"},
     *     summary="User login",
     *     operationId="login",
     *   @OA\Parameter(
     *      name="indexNum",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="User Login Successful!",
     *    
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'indexNum' => 'required|string',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation error.',$validator->errors(),400);
        }
        if(Auth::attempt([
            'indexNum'=>$request->indexNum,
             'password'=>$request->password])){
                $user = Auth::user();
                $user->token = $user->createToken('MyApp')->accessToken;

                return $this->sendResponse($user, "\nUser Login Successful!",200);
             }else{
                return $this->sendError(['error'=>'Unathorized!'],'Unauthorised',401);
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
