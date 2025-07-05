<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use App\Http\Resources\UserResource;
use App\Http\Resources\CourseInstanceResource;
use App\Http\Resources\ExamRegistrationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    
    /**
     * Get all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();
        return $this->sendResponse(
            UserResource::collection($users),
            'Users retrieved successfully');
    }

    /**
     * Get a specific user by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return $this->sendError('user not found.');
        }
        return $this->sendResponse(new UserResource($user,courses:$user->courseIntances(),examRegistrations:$user->examRegistrations(),signedRegistrations: $user->signedRegistrations()),'user retrieved successfully');
    
    }



    public function update(Request $request, User $user)
    {
        $urlSegments = explode('/', $request->getPathInfo());
        $id = end($urlSegments);
        
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        // Update the fillable fields
        $fillableFields = ['indexNum', 'name','password','email'];
        foreach ($fillableFields as $field) {
            if ($request->has($field)) {
                if($field==="password"){
                    $user->$field = Hash::make($request->$field);
                }else{
                $user->$field = $request->$field;
                }
            }
        }
        
        $user->updated_at = Carbon::now();
        $user->save();
    
        return $this->sendResponse(message: 'User updated successfully');
    }
    
    public function destroy(Request $request,User $user){
        $url_segments_array= explode('/',$request->getPathInfo());
         $id = end($url_segments_array);
        $user = User::find($id);
        if(is_null($user)){
            return $this->sendError('user not found.');
        }
        
        $user->delete();
        return $this->sendResponse(message:'user deleted successfully');  
      }
}
