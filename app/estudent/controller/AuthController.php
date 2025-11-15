<?php

namespace App\estudent\controller;
use App\estudent\domain\exceptions\BadRequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class AuthController extends BaseController
{

/**
 * @OA\Post(
 *     path="/login",
 *     tags={"Common Routes"},
 *     summary="User login",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"indexNum", "password"},
 *             @OA\Property(property="indexNum", type="string", example="2025/0000"),
 *             @OA\Property(property="password", type="string", example="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User Login Successful"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'indexNum' => 'required|string',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
              throw new BadRequestException('Bad Request, unauthorized');
        }
        if(Auth::attempt([
            'indexNum'=>$request->indexNum,
             'password'=>$request->password])){
                $user = Auth::user();
                $result['id'] = $user->id;
                $result['indexNum'] = $user->indexNum;
                $result['name'] = $user->name;
                $result['email'] = $user->email;
                $result['role'] = $user->role;
                $result['token'] = $user->createToken('MyApp')->accessToken;

                return $this->createResponse($result, "\nUser Login Successful!",200);
             }else{
                throw new BadRequestException('Bad Credentials, unauthorized');
             }
    }
     /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Common Routes"},
     *     summary="Logouts user, by deleting its token",
     *     @OA\Response(
     *         response=200,
     *         description="Success: You have logged out and the token was deleted",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error: You are not currently logged in",
     *     )
     * )
     */
    public function logout()
    {
        if(auth()->user()== null){
            throw new BadRequestException('Error: You are not currently logged in');
        }
        auth()->user()->tokens()->delete();
        return $this->createResponse(message:'Success: You have logged out and the token was deleted');
    }
}
