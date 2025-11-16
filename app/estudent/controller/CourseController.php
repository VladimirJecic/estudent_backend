<?php
namespace App\estudent\controller;

use App\estudent\domain\ports\input\CourseInstanceService;
use App\estudent\domain\ports\input\GetReportDataForCourseInstance;
use App\estudent\domain\ports\input\model\CourseInstanceFilters;
use App\estudent\controller\model\resources\CourseInstanceResource;
use Illuminate\Http\Request;

class CourseController extends BaseController
{
    private readonly CourseInstanceService $courseInstanceService;
    private readonly GetReportDataForCourseInstance $getReportDataForCourseInstanceService;

    public function __construct(
        CourseInstanceService $courseInstanceService,
        GetReportDataForCourseInstance $getReportDataForCourseInstanceService
    ) {
        $this->courseInstanceService = $courseInstanceService;
        $this->getReportDataForCourseInstanceService = $getReportDataForCourseInstanceService;
    }

    /**
     * @OA\Get(
     *     path="/admin/course-instances",
     *     summary="Get paginated course instances with filters",
     *     operationId="getCourseInstances",
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
     *         name="search-text",
     *         in="query",
     *         required=false,
     *         description="Search by course name or semester year",
     *         @OA\Schema(type="string", example="matematika")
     *     ),
     *     @OA\Parameter(
     *         name="semester-id",
     *         in="query",
     *         required=false,
     *         description="Filter by semester ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of course instances"
     *     )
     * )
    */
    public function getCourseInstancesWithFilters(Request $request)
    {
        $courseInstanceFilters = new CourseInstanceFilters($request->all());
        $paginatedCourseInstances = $this->courseInstanceService->getAllCourseInstancesWithFilters($courseInstanceFilters);
        return $this->createResponse([
            'content' => CourseInstanceResource::collection($paginatedCourseInstances->items()),
            'totalPages' => $paginatedCourseInstances->lastPage(),
            'totalElements' => $paginatedCourseInstances->total(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/admin/course-report-data/{courseInstanceId}",
     *     tags={"Admin Routes"},
     *     summary="Get statistics report data for a course instance",
     *     operationId="getCourseInstanceReportData",
     *     security={{"passport": {}}},
     *     @OA\Parameter(
     *         name="courseInstanceId",
     *         in="path",
     *         required=true,
     *         description="ID of the course instance to generate report data for",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course instance statistics report data",
     *         @OA\JsonContent(ref="#/components/schemas/CourseSemesterReportDTO")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course instance not found"
     *     )
     * )
     */
    public function getReportDataForCourseInstance(int $courseInstanceId)
    {
        $report = $this->getReportDataForCourseInstanceService->getReportDataForCourseInstance($courseInstanceId);
        return $this->createResponse($report);
    }
}
