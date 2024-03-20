<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExamRegistrationResource;
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

        $passed = isset($_GET['passed']) ? $_GET['passed'] : false;
        $failed = isset($_GET['failed']) ? $_GET['failed'] : false;
        $notGraded = isset($_GET['notGraded']) ? $_GET['notGraded'] : false;

        $student = auth()->user();

        $userRegistrations = ExamRegistration::with('student','courseExam','signedBy')->where('student_id', $student->id)->get();
        $marks = [];
        if($passed){
            $marks = array_merge($marks, [6,7,8,9,10]);
        }
        if($failed){
            $marks = array_merge($marks, [5]);
        }
        if($notGraded){
            $marks = array_merge($marks, [-1]);
        }
        $wantedRegistrations = $userRegistrations->count() > 0 ? $userRegistrations->whereIn('mark', $marks) : [];

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
        $examRegistration = ExamRegistration::create([
            'course_id' => $request->course_id,
            'exam_period_id' => $request->exam_period_id,
            'student_id' => $request->student_id,
            'mark' => -1,
        ]);

        return $this->sendResponse(message: 'ExamRegistration is stored successfully.');
   
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
