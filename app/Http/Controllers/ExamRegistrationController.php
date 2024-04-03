<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExamRegistrationResource;
use App\Models\CourseExam;
use App\Models\User;
use App\Models\ExamRegistration;
use Carbon\Carbon;
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
            $student = auth()->user();


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
    public function notGraded(Request $request){
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
            $notGradedRegistrations = $userRegistrations->where('signed_by_id','=', null);

            foreach($notGradedRegistrations as $er){
                    $er->courseExam->load('examPeriod');
            }

            $result['examRegistrations'] = ExamRegistrationResource::collection($notGradedRegistrations);

            return $this->sendResponse($result, 'Not graded Exam registrations retrieved successfully');
    }
    public function forGrading(Request $request){
            $admin= auth()->user();
            $courses = $admin->courses()->get();
            $courseExams = null;
            $examRegistrations = null;
            foreach($courses as $c){
                $courseExams[] = $c->courses()->get();
            }
            foreach($courseExams as $ce){
                $examRegistrations[] = $ce->examRegistrations()->where('signed_by_id','=', null)->get();
            }

            $result['examRegistrations'] = ExamRegistrationResource::collection($examRegistrations);

            return $this->sendResponse($result, 'Exam registrations for grading retrieved successfully');
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
        $courseExam = CourseExam::where('course_id',$request->course_id)->where('exam_period_id', $request->exam_period_id)->with('examPeriod');
        if(!$courseExam->exists()){
            return $this->sendError('Validation error', 'CourseExam with provided course_id:'.$request->course_id.", and exam_period_id:".$request->exam_period_id." doesn't exist");
        }
        $alreadyExists =  ExamRegistration::where([
            ['course_id', '=', $request->course_id],
            ['exam_period_id', '=', $request->exam_period_id],
            ['student_id', '=', $request->student_id],
        ])->exists();
        if($alreadyExists){
            return $this->sendError('Validation error', 'ExamRegistration for provided course_id:'.$request->course_id.", and exam_period_id:".$request->exam_period_id." and student_id:".$request->student_id." already exist",409);
        }
        if($courseExam->get()[0]->examPeriod->dateRegisterEnd < Carbon::now() || $courseExam->get()[0]->examPeriod->dateRegisterEnd < Carbon::now()){
            return $this->sendError('Validation error','Registration no longer in progress', 400);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_period_id' => ['required', 'int', 'exists:exam_periods,id'],
            'course_id' => ['required', 'int', 'exists:courses,id'],
            'student_id' => ['required', 'int', 'exists:users,id'],
         ]);

        if($validator->fails()){
            return $this->sendError('Validation error.', $validator->errors());
        }

        $examRegistration = ExamRegistration::where([
            ['course_id','=', $request->course_id],
            ['exam_period_id','=', $request->exam_period_id],
            ['student_id','=',  $request->student_id],
        ])->first();

        if ($examRegistration) {
            $examRegistration->mark = isset($_GET['mark']) ?  $request->mark :  $examRegistration->mark ;
            $examRegistration->comment = isset($_GET['comment']) ?  $request->comment :  $examRegistration->comment ;
            $examRegistration->signed_by_id = isset($_GET['signed_by_id']) ?  $request->signed_by_id :  $examRegistration->signed_by_id ;
            $examRegistration->save();
            return $this->sendResponse(code: 204);
        } else {
            return $this->sendError([], error_messages: 'ExamRegistration not found', code: 404);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_period_id' => ['required', 'int', 'exists:exam_periods,id'],
            'course_id' => ['required', 'int', 'exists:courses,id'],
            'student_id' => ['required', 'int', 'exists:users,id'],
         ]);

        if($validator->fails()){
            return $this->sendError('Validation error.', $validator->errors());
        }

        $examRegistration = ExamRegistration::where([
            ['course_id','=', $request->course_id],
            ['exam_period_id','=', $request->exam_period_id],
            ['student_id','=',  $request->student_id],
        ])->first();

        if ($examRegistration) {
            // ExamRegistration::destroy($examRegistration->getKey());
            $examRegistration->delete();
            return $this->sendResponse(code: 204);
        } else {
            return $this->sendError([], error_messages: 'ExamRegistration not found', code: 404);
        }
    }

}
