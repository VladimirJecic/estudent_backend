<?php

namespace App\Http\Controllers;

use App\Contracts\input\model\ExamRegistrationFilters;
use App\Http\Requests\GetNotGradedForStudentRequest;
use App\Http\Resources\ExamRegistrationResource;
use App\Http\Requests\StoreExamRegistrationRequest;
use App\Contracts\input\ExamRegistrationService;
use App\Contracts\input\GetNotGradedExamRegistrations;
use App\Http\Requests\IndexExamRegistrationRequest;
use App\Http\Requests\UpdateExamRegistrationRequest;

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
    public function index(IndexExamRegistrationRequest $request)
    {
       $examRegistrationFilters = new ExamRegistrationFilters( $request->validated());

        $examRegistrations = $this->examRegistrationService->getAllExamRegistrationsWithFilters( $examRegistrationFilters);
        $result['examRegistrations'] = ExamRegistrationResource::collection($examRegistrations);

        return $this->sendResponse($result, 'Exam registrations retrieved successfully');
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
    public function store(StoreExamRegistrationRequest $request)
    {
        $dto = $request->toDto();
        $this->examRegistrationService->saveExamRegistration($dto);    
        return $this->sendResponse([], 'ExamRegistration stored successfully.', 201);
    }
    
    /**
     * @OA\Put(
     *     path="/exam-registrations/{examRegistrationId}",
     *     tags={"Admin Routes"},
     *     summary="Update existing exam registration",
     *     operationId="exam-registrations/update",
     *     security={{"passport": {*}}},

    *     @OA\Parameter(
    *         name="examRegistrationId",
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
    public function update(UpdateExamRegistrationRequest $request, int $examRegistrationId)
    {
        $dto = $request->toDto();
        $updatedExamRegistration = $this->examRegistrationService->updateExamRegistration( $examRegistrationId, $dto );

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
        $this->examRegistrationService->deleteExamRegistration($examRegistrationId);

        return $this->sendResponse(code: 204);
    }

        /**
     * @OA\Get(
     *     path="/exam-registrations/notGraded/{student_id}",
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
    public function getNotGradedForStudent(GetNotGradedForStudentRequest $request,int $studentId){

            $notGradedRegistrations = $this->getNotGradedRegistrationsService->getAllForStudentId( $studentId );

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
    public function getAllNotGradedForAdmin(){
        
            $examRegistrations = $this->getNotGradedRegistrationsService->getAllForAdmin();
            $result['examRegistrations'] = ExamRegistrationResource::collection($examRegistrations);
            return $this->sendResponse($result, 'All not graded exam registrations retrieved successfully');
    }


}
