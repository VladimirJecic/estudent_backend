<?php

namespace App\Http\Controllers;
use App\Models\ExamPeriod;
use App\Models\CourseExam;
use App\Models\ExamRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\CourseExamResource;
use Validator;

class CourseExamController extends BaseController
{
    /**
     * Display a listing of the resource.
     */


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'examPeriod' => ['required', 'string', 'exists:exam_periods,name'],
            'indexNum' => ['required', 'string', 'exists:users,indexNum'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }

        $input = $request->all();

        // Retrieve ExamPeriod with exams
        $examPeriod = ExamPeriod::where('name', $input['examPeriod'])->with('exams')->first();

        // Retrieve User ID
        $userId = User::where('indexNum', $input['indexNum'])->value('id');

        // Retrieve user registrations with student and courseExam relationships
        $userRegistrations = ExamRegistration::with(['student', 'courseExam'])->where('student_id', $userId);

        // Retrieve passed courses
        $passedCourses = $userRegistrations->whereBetween('mark', [6, 10])->pluck('courseExam.course_id')->toArray();

        // Filter available course exams
        $availableCourseExams =  $examPeriod->exams->reject(fn ($courseExam) => 
            in_array($courseExam->course_id, $passedCourses)
        );
        $result['availableCourseExams'] = CourseExamResource::collection($availableCourseExams);

        return $this->sendResponse($result, 'Available CourseExams retrieved successfully');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseExam $courseExam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseExam $courseExam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseExam $courseExam)
    {
        //
    }
}
