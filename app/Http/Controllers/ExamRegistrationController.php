<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExamRegistrationResource;
use App\Models\CourseExam;
use App\Models\User;
use App\Models\ExamRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamRegistrationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
    {

        $excludePassed = isset($_GET['excludePassed']) ? $_GET['excludePassed'] : false;
        $excludeFailed = isset($_GET['excludeFailed']) ? $_GET['excludeFailed'] : false;
        if(isset($_GET['student_id'])){
            if(User::where("id",$_GET['student_id'])->exists()){
                $student = User::where("id",$_GET['student_id']);
            }else{
                 return $this->sendError('Validation error.', "Student with id".$_GET['student_id']."not found", 404);
            }
        }else{
            $student = auth()->user();
        }

        $userRegistrations = ExamRegistration::with('student','courseExam','signedBy')->where('student_id', $student->id)->get();
        $signedUserRegistrations = $userRegistrations->where('signed_by_id','<>', null);
        $marks = [];
        if(!$excludePassed){
            $marks = array_merge($marks, [6,7,8,9,10]);
        }
        if(!$excludeFailed){
            $marks = array_merge($marks, [5]);
        }
        $wantedRegistrations = $signedUserRegistrations->count() > 0 ? $signedUserRegistrations->whereIn('mark', $marks) : [];

        foreach($wantedRegistrations as $er){
                  $er->courseExam->load('examPeriod');
        }

        $result['examRegistrations'] = ExamRegistrationResource::collection($wantedRegistrations);

        return $this->sendResponse($result, 'Exam registrations retrieved successfully');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer',
            'exam_period_id' => 'required|integer',
            'student_id' => 'required|integer',

        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors(), 400);
        }
        $courseExam = CourseExam::where('course_id',$request->course_id)->where('exam_period_id', $request->exam_period_id);
        if(!$courseExam->exists()){
            return $this->sendError('Validation error', 'CourseExam with provided course_id:'.$request->course_id.", and exam_period_id:".$request->exam_period_id." doesn't exist");
        }
        $alreadyExists = $courseExam->where('student_id','=',$request->student_id)->exists();
        if($alreadyExists){
            return $this->sendError('Validation error', 'ExamRegistration for provided course_id:'.$request->course_id.", and exam_period_id:".$request->exam_period_id." and student_id:".$request->student_id." already exist",409);
        }

        $examRegistration = new ExamRegistration();
        $examRegistration->course_id = $request->course_id;
        $examRegistration->exam_period_id = $request->exam_period_id;
        $examRegistration->student_id = $request->student_id;
        $examRegistration->mark = $request->mark != null ? $request->mark: 5;
        $examRegistration->save();
         
        return $this->sendResponse([],message: 'ExamRegistration stored successfully.',code: 201);
   
    }

    /**
     * Display the specified resource.
     */
    public function show(ExamRegistration $examRegistration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExamRegistration $examRegistration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExamRegistration $examRegistration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamRegistration $examRegistration)
    {
        //
    }
}
