<?php

namespace App\estudent\controller;

use App\estudent\domain\ports\input\model\ExamRegistrationFilters;
use App\estudent\controller\model\requests\GetNotGradedForStudentRequest;
use App\estudent\controller\model\resources\ExamRegistrationResource;
use App\estudent\controller\model\requests\SubmitExamRegistrationRequest;
use App\estudent\domain\ports\input\ExamRegistrationService;
use App\estudent\domain\ports\input\GetNotGradedExamRegistrations;
use App\estudent\controller\model\requests\GetExamRegistrationsRequest;
use App\estudent\controller\model\requests\UpdateExamRegistrationRequest;

class ExamRegistrationController extends BaseController
{
    private  readonly ExamRegistrationService $examRegistrationService;
    private  readonly GetNotGradedExamRegistrations $getNotGradedRegistrationsService;

    public function __construct(ExamRegistrationService $examRegistrationService,GetNotGradedExamRegistrations $getNotGradedRegistrationsService)
    {
        $this->examRegistrationService = $examRegistrationService;
        $this->getNotGradedRegistrationsService = $getNotGradedRegistrationsService;
    }
    /**
    * @OA\Get(
    *     path="/admin/exam-registrations",
     *     tags={"Admin Routes"},
     *     summary="Retrieve exam registrations",
     *     operationId="exam-registrations/index",
     *     security={
     *              {"passport": {*}}
     *      },
     *     @OA\Parameter(
     *         name="search-text",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page-size",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="include-not-graded",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="include-failed",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="include-passed",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of exam registrations"
     *     )
     * )
    */
    public function getExamRegistrationsWithFilters(GetExamRegistrationsRequest $request)
    {
        $examRegistrationFilters = $request->toDto();
        $paginatedExamRegistrations = $this->examRegistrationService->getAllExamRegistrationsWithFilters($examRegistrationFilters);
        return response()->json([
            'content' => ExamRegistrationResource::collection($paginatedExamRegistrations->items()),
            'total-pages' => $paginatedExamRegistrations->lastPage(),
            'total-elements' => $paginatedExamRegistrations->total(),
        ]);
    }
    /**
     * Retrieve exam registrations for the authenticated student, applying filters but forcing student_id to user->id
     *
     * @OA\Get(
     *     path="/exam-registrations",
     *     tags={"Common Routes"},
     *     summary="Retrieve exam registrations for the authenticated student",
     *     security={
     *         {"passport": {*}}
     *     },
     *     @OA\Parameter(
     *         name="search-text",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page-size",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="include-not-graded",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="include-failed",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="include-passed",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="student-id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of exam registrations"
     *     )
     * )
    */
    public function getExamRegistrationsWithFiltersForStudent(GetExamRegistrationsRequest $request)
    {
        $examRegistrationFilters = $request->toDto();
        $examRegistrationFilters->studentId = auth()->id();
        $paginatedExamRegistrations = $this->examRegistrationService->getAllExamRegistrationsWithFilters($examRegistrationFilters);
        return response()->json([
            'content' => ExamRegistrationResource::collection($paginatedExamRegistrations->items()),
            'total-pages' => $paginatedExamRegistrations->lastPage(),
            'total-elements' => $paginatedExamRegistrations->total(),
        ]);
    }
        /**
     * @OA\Get(
     *     path="/exam-registrations/not-graded",
     *     tags={"Common Routes"},
     *     summary="Retrieve not graded exam registrations only for logged in/passed student",
     *     security={
     *              {"passport": {*}}
     *      },
    *   @OA\Parameter(
    *      name="student-id",
     *      in="query",
     *      required=true,
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
    public function getNotGradedForStudent(GetNotGradedForStudentRequest $request){
        $studentId = $request->getStudentId();
        $notGradedRegistrations = $this->getNotGradedRegistrationsService->getNotGradedExamRegistrationsForStudentId( $studentId );

        $result= ExamRegistrationResource::collection($notGradedRegistrations);

        return $this->createResponse($result, 'Not graded exam registrations retrieved successfully');
    }
     /**
    * @OA\Get(
    *     path="/admin/exam-registrations/not-graded/all",
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
    public function getAllNotGradedForAdmin(){
        
            $examRegistrations = $this->getNotGradedRegistrationsService->getNotGradedExamRegistrations();
            $result = ExamRegistrationResource::collection($examRegistrations);
            return $this->createResponse($result, 'All not graded exam registrations retrieved successfully');
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SubmitExamRegistrationDTO")
     *     ),
     *
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
    // POST /exam-registrations
    public function createExamRegistration(SubmitExamRegistrationRequest $request)
    {
        $dto = $request->toDto();
        $examRegistration = $this->examRegistrationService->createExamRegistration($dto);
        $result = new ExamRegistrationResource($examRegistration);
        return $this->createResponse($result, 'ExamRegistration stored successfully.', 201);
    }
    
    /**
    * @OA\Put(
    *     path="/admin/exam-registrations/{examRegistrationId}",
     *     tags={"Admin Routes"},
     *     summary="Update existing exam registration",
     *     operationId="exam-registrations/update",
     *     security={{"passport": {*}}},
     *
    *     @OA\Parameter(
    *         name="exam-registration-id",
     *         in="path",
     *         required=true,
     *         description="ID of the exam registration to update",
     *         @OA\Schema(type="integer")
     *     ),
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExamRegistrationUpdateDTO")
     *     ),
     *
     *     @OA\Response(response=204, description="Updated successfully"),
     *     @OA\Response(response=403, description="Error: Forbidden"),
     *     @OA\Response(response=404, description="ExamRegistration not found")
     * )
     */
    // PUT /exam-registrations/{examRegistrationId}
    public function updateExamRegistration(UpdateExamRegistrationRequest $request, int $examRegistrationId)
    {
        $dto = $request->toDto();
        $this->examRegistrationService->updateExamRegistration($examRegistrationId, $dto);
        return $this->createResponse(code: 204);
    }






        /**
     * @OA\Delete(
     *     path="/exam-registrations/{examRegistrationId}",
     *     tags={"Common Routes"},
     *     summary="Delete existing exam registration",
     *     operationId="exam-registrations/destroy",
     *     security={{"passport": {*}}},

    *     @OA\Parameter(
    *         name="exam-registration-id",
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
    // DELETE /exam-registrations/{examRegistrationId}
    public function deleteExamRegistration(int $examRegistrationId)
    {
        $this->examRegistrationService->deleteExamRegistration($examRegistrationId);
        return $this->createResponse(code: 204);
    }

}
