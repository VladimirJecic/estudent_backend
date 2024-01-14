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
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }

        $input = $request->all();

        // Retrieve ExamPeriod with exams
        $examPeriod = ExamPeriod::where('name', $input['examPeriod'])->with('exams')->first();

        // Retrieve authenticated user
        $authenticatedUser = auth()->user();

        // Retrieve user registrations with student and courseExam relationships
        $userRegistrations = ExamRegistration::with(['student', 'courseExam'])->where('student_id', $authenticatedUser->id);

        // Retrieve passed courses
         $passedExams = $userRegistrations->count() > 0 ? $userRegistrations->whereBetween('mark', [6, 10])->pluck('courseExam.course_id')->toArray() : [];

        // Filter remaining course exams
        $remainingExams =  $examPeriod->exams->reject(fn ($courseExam) => 
            in_array($courseExam->course_id, $passedExams)
        );
        $result['availableCourseExams'] = CourseExamResource::collection($remainingExams);

        return $this->sendResponse($result, 'Remaining CourseExams for '.$examPeriod->name.' retrieved successfully');
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
