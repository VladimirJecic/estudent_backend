<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use App\Http\Resources\UserResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ExamRegistrationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        return $this->sendResponse(new UserResource($user),'user retrieved successfully');
    
    }

    /**
     * Get all courses for a specific user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserCourses($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return $this->sendError('user not found.');
        }
        $courses = $user->courses();
        return $this->sendResponse(CourseResource::collection($courses),'Courses retrieved successfully');
    }

    /**
     * Get all exam registrations for a specific user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserExamRegistrations($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return $this->sendError('user not found.');
        }
        $examRegistrations = $user->examRegistrations();
        return $this->sendResponse(ExamRegistrationResource::collection($examRegistrations),'Exam Registrations retrieved successfully');
    }

    /**
     * Get all signed registrations for a specific user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserSignedRegistrations($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return $this->sendError('user not found.');
        }
        $examRegistrations = $user->signedRegistrations();
        return $this->sendResponse(ExamRegistrationResource::collection($examRegistrations),'Signed exam registrations retrieved successfully');
    }


    public function update(Request $request,User $user){
        $input= $request->all();
        $validator = Validator::make($input,[
            'name'=>'required',
            'indexNum'=>'required',
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation error.',$validator->errors());
        }
        $user = User::find($input['id']);
        $user->name = $input['name'];
        $user->name = $input['email'];
        $user->detail = $input['detail'];
        $user->updated_at = Carbon::now();
        $user->save();


        return $this->sendResponse(new UserResource($user), "\nuser updated successfully");
      
    }
    
    public function destroy(Request $request,User $user){
        $input= $request->all();
        $validator = Validator::make($input,[
            'id'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation error.',$validator->errors());
        }
        
        $user->delete();
        return $this->sendResponse([],'user deleted successfully');  
      }
}
