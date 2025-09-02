<?php

namespace App\Http\Controllers;
use App\Exceptions\BadRequestException;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Validator;
class AuthController extends BaseController
{

    //  /**
    //  * @OA\Post(
    //  *     path="/estudent/api/register",
    //  *     tags={"Admin Routes"},
    //  *     summary="Register new user",
    //  *   @OA\Parameter(
    //  *      name="indexNum",
    //  *      in="query",
    //  *      required=true,
    //  *      @OA\Schema(
    //  *           type="string"
    //  *      )
    //  *   ),
    //  *   @OA\Parameter(
    //  *      name="name",
    //  *      in="query",
    //  *      required=true,
    //  *      @OA\Schema(
    //  *           type="string"
    //  *      )
    //  *   ),
    //  *   @OA\Parameter(
    //  *      name="password",
    //  *      in="query",
    //  *      required=true,
    //  *      @OA\Schema(
    //  *          type="string"
    //  *      )
    //  *   ),
    //  *   @OA\Parameter(
    //  *      name="confirmPassword",
    //  *      in="query",
    //  *      required=true,
    //  *      @OA\Schema(
    //  *          type="string"
    //  *      )
    //  *   ),
    //  *     @OA\Response(
    //  *         response=201,
    //  *         description="User Registered Successfully!",
    //  *    
    //  *     ),
    //  *     @OA\Response(
    //  *         response=400,
    //  *         description="Validation errors:...",
    //  *    
    //  *     ),
    //  *     @OA\Response(
    //  *         response=409,
    //  *         description="User with the same indexNum already exists.",
    //  *    
    //  *     ),
    //  * )
    //  */
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'indexNum' => 'required',
    //         'name' => 'required',
    //         'password' => 'required',
    //         'confirmPassword' => 'required|same:password'
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->sendError('Validation error.', $validator->errors(),400);
    //     }

    //     $input = $request->all();

    //     if (User::where('indexNum', $input['indexNum'])->exists()) {
    //         return $this->sendError('User with the same indexNum already exists.', [], 409);
    //     }

    //     $trimmedName = trim($input['name']);
    //     $words = explode(' ', $trimmedName);
    //     $generatedEmail = strtolower(substr($words[0], 0, 1).substr($words[count($words)-1], 0, 1) ). str_replace('/', '', $input['indexNum']) . '@student.fon.bg.ac.rs';


    //     $input['password'] = bcrypt($input['password']);
    //     $input['email'] = $generatedEmail;
    //     $input['role'] = 'student';

    //     $user = User::create($input);

    //     $result['indexNum'] = $user->indexNum;
    //     $result['name'] = $user->name;
    //     $result['email'] = $user->email;
    //     $result['token'] = $user->createToken('MyApp')->accessToken;
    //     $result['role'] = $user->role;

    //     return $this->sendResponse($result, "User Registered Successfully!",201);
    // }

/**
 * @OA\Post(
 *     path="/login",
 *     tags={"Common Routes"},
 *     summary="User login",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"indexNum", "password"},
 *             @OA\Property(property="indexNum", type="string", example="2023/0000"),
 *             @OA\Property(property="password", type="string", example="test")
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

                return $this->sendResponse($result, "\nUser Login Successful!",200);
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
        return $this->sendResponse(message:'Success: You have logged out and the token was deleted');
    }
}
