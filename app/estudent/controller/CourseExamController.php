<?php

namespace App\estudent\controller;
use App\estudent\domain\ports\input\GetReportForCourseExam;
use App\estudent\domain\ports\input\model\CourseExamFilters;
use App\estudent\controller\model\requests\GetRemainingCourseExamsRequest;
use Illuminate\Http\Request;
use App\estudent\controller\model\resources\CourseExamResource;
use App\estudent\domain\ports\input\GetRegisterableCourseExams;
use App\estudent\domain\ports\input\GetRemainingCourseExams;
use App\estudent\domain\ports\input\CourseExamService;
use App\estudent\controller\model\requests\CourseExamReportRequest;

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
     *     path="/admin/course-exams",
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
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="page-size",
     *         in="query",
     *         required=false,
     *         description="Page size",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="course-name",
     *         in="query",
     *         required=false,
     *         description="Course name",
     *         @OA\Schema(type="string", example="matematika")
     *     ),
     *     @OA\Parameter(
     *         name="date-from",
     *         in="query",
     *         required=false,
     *         description="Start date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date", example="2025-06-01")
     *     ),
     *     @OA\Parameter(
     *         name="date-to",
     *         in="query",
     *         required=false,
     *         description="End date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date", example="2025-06-30")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of course exams"
     *     )
     * )
    */
    public function getCourseExamsWithFilters(Request $request)
    {
        $courseExamFilters = new CourseExamFilters($request->all());

        $paginatedCourseExams = $this->courseExamService->getAllCourseExamsWithFilters($courseExamFilters);

        return response()->json([
            'content' => CourseExamResource::collection($paginatedCourseExams->items()),
            'totalPages' => $paginatedCourseExams->lastPage(),
            'totalElements' => $paginatedCourseExams->total(),
        ]);
    }
            

     /**
     * @OA\Get(
     *     path="/course-exams/remaining-course-exams",
     *     tags={"Common Routes"},
     *     summary="Get all remaining course exams for currently logged-in student in provided exam period",
     *     security={
     *              {"passport": {*}}
     *      },
    *     @OA\Parameter(
    *         name="for-exam-period-id",
     *         in="query",
     *         required=true,
     *         description="Exam period ID",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Remaining CourseExams retrieved successfully",
     *     ),
     * )
     */
    public function getRemainingCourseExams(GetRemainingCourseExamsRequest $request)
    {
        $examPeriodId = $request->query('for-exam-period-id');
        $remainingCourseExams = $this->getRemainingCourseExamsService->getRemainingCourseExams($examPeriodId);
        $result = CourseExamResource::collection($remainingCourseExams);
        return $this->createResponse($result, 'Remaining CourseExams retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/course-exams/registerable-course-exams",
     *     tags={"Common Routes"},
     *     summary="Get all course-exams for current user that he did not register for yet in any active exam period",
     *     security={
     *              {"passport": {*}}
     *      },
     *     @OA\Response(
     *         response=200,
     *         description="Registerable CourseExams retrieved successfully",
     *     ),
     * )
     */
    public function getRegisterableCourseExams()
    {
        $registerableCourseExams = $this->getRegisterableCourseExamsService->getRegisterableCourseExams();
        $result = CourseExamResource::collection($registerableCourseExams);    
        return $this->createResponse($result, 'Registerable CourseExams retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/admin/course-exam-reports/{courseExamId}",
     *     tags={"Admin Routes"},
     *     summary="Download Excel report for a course exam identified by courseExamId",
     *     operationId="getCourseExamReport",
     *     security={{"passport": {}}},
    *     @OA\Parameter(
    *         name="course-exam-id",
    *         in="path",
    *         required=true,
    *         description="ID of the CourseExam to generate the report for, will include all exam registrations for that course exam",
    *         @OA\Schema(type="integer", example=1)
    *     ),
    * 
    *     @OA\Response(
    *         response=200,
    *         description="Excel file download",
    *         @OA\MediaType(
    *             mediaType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    *             @OA\Schema(type="string", format="binary")
    *         )
    *     )
    * )
    */
public function getReportForCourseExam(CourseExamReportRequest $request, int $courseExamId)
{
    $report = $this->getReportForCourseExamService->getReportForCourseExam($courseExamId);
    return $report;
}

}