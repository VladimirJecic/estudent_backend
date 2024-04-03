<?php

namespace App\Http\Controllers;
use App\Models\ExamPeriod;
use App\Models\ExamRegistration;
use Illuminate\Http\Request;
use App\Http\Resources\CourseExamResource;
use Validator;
use Carbon\Carbon;

class CourseExamController extends BaseController
{

    /**
     * Display the specified resource.
     */
    public function getRemainingCourseExams(Request $request, $examPeriod)
    {
        $validator = Validator::make(['examPeriod' => $examPeriod], [
        'examPeriod' => ['required', 'string', 'exists:exam_periods,name'],
         ]);

        if($validator->fails()){
            return $this->sendError('Validation error.', $validator->errors());
        }

        $examPeriod = ExamPeriod::with('exams')->where('name',$examPeriod)->first();
        
        $student = auth()->user();
        
        $enrolledCourses = $student->courses()->pluck('id')->toArray();

        $courseExams_where_enrolled = $examPeriod->exams->reject(fn ($courseExam)=>
            !in_array($courseExam->course_id,$enrolledCourses)
        );

        $examAttempts = ExamRegistration::with('student','courseExam')->where('student_id', $student->id)->get();

        $successfulAttempts = $examAttempts->count() > 0 ? $examAttempts->whereBetween('mark', [6, 10])->pluck('courseExam.course_id')->toArray() : [];

        $courseExams_remaining =  $courseExams_where_enrolled->reject(fn ($courseExam) => 
            in_array($courseExam->course_id, $successfulAttempts)
        );
        $result['courseExams'] = CourseExamResource::collection($courseExams_remaining);

        return $this->sendResponse($result, 'Remaining CourseExams for '.$examPeriod->name.' retrieved successfully');
    }


    public function getRegistableCourseExams(Request $request)
    {
        $currentDate = Carbon::now();
        $examPeriods = ExamPeriod::with('exams')->where('dateRegisterStart', '<=', $currentDate)
        ->where('dateRegisterEnd', '>=', $currentDate)
        ->get();
             
        $student = auth()->user();

        $courseExams = $examPeriods->flatMap(fn ($examPeriod) => $examPeriod->exams);
        $examRegistrations = ExamRegistration::with('student','courseExam')->where('student_id', $student->id);

        $courseExamsWithoutRegistrations  = $courseExams->reject(fn ($courseExam) => 
            in_array($courseExam->course_id, $examRegistrations->pluck('course_id')->toArray())
        );

        $result['courseExams'] = CourseExamResource::collection($courseExamsWithoutRegistrations);

        return $this->sendResponse($result, 'Registable CourseExams retrieved successfully');
    }


}