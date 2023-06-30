<?php

namespace App\Http\Controllers;

use App\Models\CourseExam;
use Illuminate\Http\Request;

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
    public function showForExam(Request $request,CourseExam $courseExam)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer',
            'exam_period' => 'required|integer',
            // 'hall' => 'required|string',
            // 'examDateTime' => 'required|date_format:Y-m-d H:i:s',
        ]);
        $courseExam = User::find($id);
        if(is_null($user)){
            return $this->sendError('user not found.');
        }
        if($validator->fails()){
            return $this->sendError('Validation error.',$validator->errors(),400);
        }
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
