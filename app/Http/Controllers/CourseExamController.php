<?php

namespace App\Http\Controllers;
use App\Contracts\input\GetReportForCourseExam;
use App\Contracts\input\model\CourseExamFilters;
use App\Http\Requests\GetRemainingCourseExamsRequest;
use App\Http\Requests\GetRegisterableCourseExamsRequest;
use Illuminate\Http\Request;
use App\Http\Resources\CourseExamResource;
use App\Contracts\input\GetRegisterableCourseExams;
use App\Contracts\input\GetRemainingCourseExams;
use App\Contracts\input\CourseExamService;
use App\Http\Requests\CourseExamReportRequest;

class CourseExamController extends BaseController
{
    private readonly CourseExamService $courseExamService;
    private readonly GetRegisterableCourseExams $getRegisterableCourseExamsService;

    private readonly GetRemainingCourseExams $getRemainingCourseExamsService;

    private readonly GetReportForCourseExam $getReportForCourseExamService;
    public function __construct(
        CourseExamService  $courseExamService,
        GetRegisterableCourseExams $getRegisterableCourseExamsService,
        GetRemainingCourseExams $getRemainingCourseExamsService,
        GetReportForCourseExam $getReportForCourseExam)
    {
        $this->courseExamService = $courseExamService;
        $this->getRegisterableCourseExamsService = $getRegisterableCourseExamsService;
        $this->getRemainingCourseExamsService = $getRemainingCourseExamsService;
        $this->getReportForCourseExamService = $getReportForCourseExam;
    }

    /**
     * @OA\Get(
     *     path="/course-exams",
     *     summary="Get paginated course exams with filters",
     *     operationId="getCourseExams",
     *     tags={"Admin Routes"},
     *     security={
     *             {"passport": {*}}
     *      },
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number (starting from 1)",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="page-size",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="course-name",
     *         in="query",
     *         required=false,
     *         description="Filter by course name (partial match)",
     *         @OA\Schema(type="string", example="matematika")
     *     ),
     *     @OA\Parameter(
     *         name="date-from",
     *         in="query",
     *         required=false,
     *         description="Start date filter (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date", example="2025-06-01")
     *     ),
     *     @OA\Parameter(
     *         name="date-to",
     *         in="query",
     *         required=false,
     *         description="End date filter (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date", example="2025-06-30")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of course exams",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="content",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CourseExam")
     *             ),
     *             @OA\Property(property="totalPages", type="integer", example=5),
     *             @OA\Property(property="totalElements", type="integer", example=47)
     *         )
     *     )
     * )
    */
    public function index(Request $request)
    {
        $courseExamFilters = new CourseExamFilters($request->query());
        
       
        $paginatedCourseExams = $this->courseExamService->getAllCourseExamsWithFilters( $courseExamFilters );

        return response()->json([
            'content' => CourseExamResource::collection($paginatedCourseExams->items()),
            'totalPages' => $paginatedCourseExams->lastPage(),
            'totalElements' => $paginatedCourseExams->total(),
        ]);
    }
            

     /**
     * @OA\Get(
     *     path="/course-exams/{examPeriodId}/remaining-course-exams",
     *     tags={"Common Routes"},
     *     summary="Get all remaining course exams for exam period",
     *     security={
     *              {"passport": {*}}
     *      },
     *   @OA\Parameter(
     *      name="examPeriodId",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *           example="5"
     *      )
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="Remaining CourseExams retrieved successfully",
     *    
     *     ),
     * )
     */
    public function getRemainingCourseExams(GetRemainingCourseExamsRequest $request, $examPeriodId)
    {
        $request->validated(); // Ensure request passes validation, we don't need the result
        $remainingCourseExams = $this->getRemainingCourseExamsService->getRemainingCourseExams($examPeriodId);
        $result['courseExams'] = CourseExamResource::collection($remainingCourseExams);
        return $this->sendResponse($result, 'Remaining CourseExams retrieved successfully');
    }

     /**
     * @OA\Get(
     *     path="/course-exams/{examPeriodId}/registerable-course-exams",
     *     tags={"Common Routes"},
     *     summary="Get all course-exams for current user that he can currently register for",
     *     security={
     *              {"passport": {*}}
     *      },
     *     @OA\Parameter(
     *      name="examPeriodId",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           example="5"
     *      )
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="'Registerable CourseExams retrieved successfully",
     *    
     *     ),
     * )
     */
    public function getRegisterableCourseExams(GetRegisterableCourseExamsRequest $request, $examPeriodId)
    {
        $request->validated(); // Ensure request passes validation, we don't need the result
        $registerableCourseExams = $this->getRegisterableCourseExamsService->getRegisterableCourseExams($examPeriodId);
       
        $result['courseExams'] = CourseExamResource::collection($registerableCourseExams);    
        return $this->sendResponse($result, 'Registerable CourseExams retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/course-exam-reports/{courseExamId}",
     *     tags={"Admin Routes"},
     *     summary="Download Excel report for a course exam identified by courseExamId",
     *     operationId="getCourseExamReport",
     *     security={{"passport": {}}},
     *     @OA\Parameter(
     *         name="courseExamId",
     *         in="path",
     *         required=true,
     *         description="ID of the Course",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Excel file download",
     *         @OA\MediaType(
     *             mediaType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
     *             @OA\Schema(type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No registrations found for this course exam"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed or CourseExam not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error: Course Exam with courseExamId 1 not found"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
  public function getReportForCourseExam(CourseExamReportRequest $request, int $courseExamId)
  {
     $request->validated(); // Ensure request passes validation, we don't need the result
  
    $report = $this->getReportForCourseExamService->getReportForCourseExam($courseExamId);
    return $report;
    }

}