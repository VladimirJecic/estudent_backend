<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        return $this->sendResponse(
            CourseResource::collection($courses),
            'Courses retrieved successfully'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'semester' => 'required|string',
            'espb' => 'required|integer',
            'participants' => 'array',

        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors(), 400);
        }

        $course = Course::create([
            'name' => $request->name,
            'semester' => $request->semester,
            'espb' => $request->espb,
        ]);
        if ($request->has('participants') and is_array($request->participants)) {
            $course->participants()->attach($request->participants);
        }

        return $this->sendResponse(message: 'Course is stored successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $course = Course::find($id);
        if (is_null($course)) {
            return $this->sendError('course not found.');
        }

        return $this->sendResponse(new CourseResource($course, $course->participants()->get()), 'course and its participants retrieved successfully');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $urlSegments = explode('/', $request->getPathInfo());
        $id = end($urlSegments);

        $course = Course::find($id);
        if (is_null($course)) {
            return $this->sendError([], 'Course not found.', 400);
        }
        // Update the fillable fields
        $fillableFields = ['name', 'semester', 'espb'];
        foreach ($fillableFields as $field) {
            if ($request->has($field)) {
                $course->$field = $request->$field;
            }
        }

        // Get the current participants for the course

        // Update the participants for the course
        if ($request->has('participants') and is_array($request->participants)) {
            $currentParticipants = $course->participants()->pluck('id')->toArray();
            $newParticipants = $request->participants;

            // Iterate through newParticipants and check if they already exist
            // if not, find that user in database and if it exist
            // Attach that participant
            foreach ($newParticipants as $newParticipantId) {
                if (!in_array($newParticipantId, $currentParticipants)) {
                    $user = User::find($newParticipantId);

                    if (!is_null($user)) {
                        $course->participants()->attach($user);
                    }
                }
            }
            // Iterate through currentParticipants and check if they exist in newParticipants
            // if not, they are deleted and
            // Detach that participant
            foreach ($currentParticipants as $participantId) {
                if (!in_array($participantId, $newParticipants)) {
                    $course->participants()->detach($participantId);
                }
            }
        } else {
            // If no participants are provided in the request, detach all current participants
            $course->participants()->detach();
        }

        // Save the updated course
        $course->save();

        return $this->sendResponse(message: 'Course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Course $course)
    {
        $urlSegments = explode('/', $request->getPathInfo());
        $id = end($urlSegments);

        $course = Course::find($id);
        if (is_null($course)) {
            return $this->sendError([], 'Course not found.');
        }
        $course->participants()->detach();
        $course->delete();

        return $this->sendResponse('Course deleted successfully.');
    }
}