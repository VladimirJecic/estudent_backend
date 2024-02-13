<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExamRegistrationResource;
use App\Models\ExamRegistration;
use Illuminate\Http\Request;

class ExamRegistrationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
    {
        // Retrieve authenticated user
        $authenticatedUser = auth()->user();

        // Retrieve user registrations with student and courseExam relationships
        $userRegistrations = ExamRegistration::with('student','courseExam','signedBy')->where('student_id', $authenticatedUser->id)->get();

        // Retrieve passed courses
        $passedExamRegistrations = $userRegistrations->count() > 0 ? $userRegistrations->whereBetween('mark', [6, 10]) : [];

        foreach($passedExamRegistrations as $er){
                  $er->courseExam->load('examPeriod');
        }

        $result['successfulExamRegistrations'] = ExamRegistrationResource::collection($passedExamRegistrations);

        return $this->sendResponse($result, 'Exam registrations with passing grades retrieved successfully');
    }


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
