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
     * @OA\Get(
     *     path="/exam-registrations",
     *     tags={"Common Routes"},
     *     summary="Retrieve exam registrations",
     *     operationId="exam-registrations/index",
     *     security={
     *              {"passport": {*}}
     *      },
     *   @OA\Parameter(
     *      name="excludePassed",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="excludeFailed",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="Exam registrations retrieved successfully",
     *    
     *     ),
     * )
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
    /**
     * @OA\Get(
     *     path="/exam-registrations/notGraded",
     *     tags={"Common Routes"},
     *     summary="Retrieve not graded exam registrations only for logged in/passed student",
     *     security={
     *              {"passport": {*}}
     *      },
     *   @OA\Parameter(
     *      name="student_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="Not graded exam registrations retrieved successfully",
     *    
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student with id student_id not found",
     *    
     *     ),
     * )
     */
    public function notGraded(Request $request){
            if($request->input('signed_by_id', false)){
                if(User::where("id",$request->student_id)->exists()){
                    $student = User::where("id",$request->student_id);
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

            return $this->sendResponse($result, 'Not graded exam registrations retrieved successfully');
    }
     /**
     * @OA\Get(
     *     path="/exam-registrations/notGraded/all",
     *     tags={"Admin Routes"},
     *     summary="Retrieve not graded exam registrations for all students",
     *     security={
     *              {"passport": {*}}
     *      },
     *     @OA\Response(
     *         response=200,
     *         description="All not graded exam registrations retrieved successfully",
     *    
     *     ),
     * )
     */
    public function notGraded_all(Request $request){
            $admin= auth()->user();
            $courses = $admin->courseIntances()->get();
            $examRegistrations = collect([]);
            foreach($courses as $c){
                $courseExams = $c->courseExams()->get();
                foreach($courseExams as $ce){
                 $examRegistrations = $examRegistrations->concat($ce->examRegistrations()->where('signed_by_id','=', null)->get());
                }
            }

            $result['examRegistrations'] = ExamRegistrationResource::collection($examRegistrations);

            return $this->sendResponse($result, 'All not graded exam registrations retrieved successfully');
    }

     /**
     * @OA\Post(
     *     path="/exam-registrations",
     *     tags={"Common Routes"},
     *     summary="Store new exam registration",
     *     operationId="exam-registrations/store",
     *     security={
     *              {"passport": {*}}
     *      },
     *   @OA\Parameter(
     *      name="course_exam_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="student_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *     @OA\Response(
     *         response=201,
     *         description="ExamRegistration stored successfully.",
     *    
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors:.../CourseExam with provided course_id:x, and exam_period_id:x doesn't exist/Registration no longer in progress for given exam period",
     *    
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="ExamRegistration for provided course_id:x, and exam_period_id:x and student_id:x already exist",
     *    
     *     ),
     * 
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_exam_id' => 'required|integer',
            'student_id' => 'required|integer',
        ]);
    
        $validator->after(function ($validator) use ($request) {
            $user = auth()->user();
            if ($user->role === 'student' && $request->student_id != $user->id) {
                $validator->errors()->add('student_id', 'As student, you can only register exams for yourself.');
            }
        });
    
        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors()->first(), 400);
        }
    
        $courseExam = CourseExam::with('examPeriod')->find($request->course_exam_id);
        if (!$courseExam) {
            return $this->sendError('Validation error', 'CourseExam with provided course_exam_id: '.$request->course_exam_id." doesn't exist", 400);
        }
    
        $alreadyExists = ExamRegistration::where([
            ['course_exam_id', '=', $request->course_exam_id],
            ['student_id', '=', $request->student_id],
        ])->exists();
    
        if ($alreadyExists) {
            return $this->sendError('Validation error', 'ExamRegistration for provided course_exam_id: '.$request->course_exam_id." and student_id: ".$request->student_id." already exists", 409);
        }
        $now = now();
        if ($courseExam->examPeriod->dateRegisterStart > $now || $courseExam->examPeriod->dateRegisterEnd < $now) {
            return $this->sendError('Validation error', 'Registration not in progress for given exam period', 400);
        }
    
        ExamRegistration::create([
            'course_exam_id' => $courseExam->id,
            'student_id' => $request->student_id,
            'mark' => $request->mark ?? 5,
            'hasAttended' => false,
        ]);
    
        return $this->sendResponse([], 'ExamRegistration stored successfully.', 201);
    }
    
    /**
     * @OA\Put(
     *     path="/exam-registrations/{id}",
     *     tags={"Admin Routes"},
     *     summary="Update existing exam registration",
     *     operationId="exam-registrations/update",
     *     security={{"passport": {*}}},

    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID of the exam registration to update",
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Parameter(
    *         name="mark",
    *         in="query",
    *         required=false,
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Parameter(
    *         name="comment",
    *         in="query",
    *         required=false,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="hasAttended",
    *         in="query",
    *         required=true,
    *         @OA\Schema(type="boolean")
    *     ),
    *     @OA\Response(response=204, description="Updated successfully"),
    *     @OA\Response(response=403, description="Error: Forbidden"),
    *     @OA\Response(response=404, description="ExamRegistration not found")
    * )
    */
    public function update(Request $request, int $examRegistrationId )
    {
        $examRegistration = ExamRegistration::find($examRegistrationId);

        if (!$examRegistration) {
            return $this->sendError([], 'ExamRegistration not found', 404);
        }
        $user = auth()->user();

        if ($user->role === 'student') {
            return $this->sendError([], 'Forbidden: As student, you cannot update an exam registration.', 403);
        }

        $examRegistration->mark = $request->input('mark', $examRegistration->mark);
        $examRegistration->comment = $request->input('comment', $examRegistration->comment);
        $examRegistration->signed_by_id = $request->input('signed_by_id', $user->id);
        $examRegistration->hasAttended = $request->input('hasAttended');
        $examRegistration->save();

        return $this->sendResponse(code: 204);
    }


    /**
     * @OA\Delete(
     *     path="/exam-registrations/{examRegistrationId}",
     *     tags={"Common Routes"},
     *     summary="Delete existing exam registration",
     *     operationId="exam-registrations/destroy",
     *     security={{"passport": {*}}},

    *     @OA\Parameter(
    *         name="examRegistrationId",
    *         in="path",
    *         required=true,
    *         description="ID of the exam registration to delete",
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(response=204, description="Deleted successfully"),
    *     @OA\Response(response=403, description="Forbidden"),
    *     @OA\Response(response=404, description="ExamRegistration not found")
    * )
    */
    public function destroy(int $examRegistrationId)
    {
        $examRegistration = ExamRegistration::find($examRegistrationId);

        if (!$examRegistration) {
            return $this->sendError([], 'ExamRegistration not found', 404);
        }

        $user = auth()->user();

        // If the user is a student, they can only delete their own registrations
        if ($user->role === 'student' && $examRegistration->student_id != $user->id) {
            return $this->sendError([], 'Forbidden: You can only delete your own exam registrations.', 403);
        }

        $examRegistration->delete();

        return $this->sendResponse(code: 204);
    }



}
