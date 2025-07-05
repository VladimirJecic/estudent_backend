<?php

namespace App\Http\Controllers;

use App\Models\CourseExam;
use App\Models\ExamRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ports\output\model\CourseExamReportItemDTO;
use App\ports\output\model\CourseExamReportDTO;
use App\ports\output\GenerateCourseExamReport;
class CourseExamReportController extends BaseController
{

    private GenerateCourseExamReport $excelGeneratorService;
    public function __construct(GenerateCourseExamReport $excelGeneratorService)
    {
        $this->excelGeneratorService = $excelGeneratorService;
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
  public function getReportForCourseExam(Request $request, int $courseExamId)
  {
      // Validate route params explicitly:
      $validator = Validator::make(
          ['courseExamId' => $courseExamId],
          [
              'courseExamId' => ['required', 'integer', 'exists:course_exams,id'],
              
          ]
      );
  
      if ($validator->fails()) {
          return $this->sendError(
              "Validation error: Course Exam with courseExamId {$courseExamId} not found",
              $validator->errors(),
              400
          );
      }
  
      $courseExam = CourseExam::with('examRegistrations.student')
          ->where('id', $courseExamId)
          ->firstOrFail();
      /** @var \App\Models\CourseExam $courseExam */
      $courseExamReportDTO = new CourseExamReportDTO($courseExam);
      $courseExamReportDTO->reportItemList = array_map(fn(ExamRegistration $examRegistration)=> new CourseExamReportItemDTO($examRegistration)
      ,$courseExam->examRegistrations->all());

     if(count( $courseExamReportDTO->reportItemList) == 0)
      {
        return $this->sendError('There are no registrations for this course exam', null,204);
      }
      $registrations = $courseExam->examRegistrations;
      $total = $registrations->count();
      $registrationsHasAttended = $registrations->filter(fn ($r) => $r->hasAttended)->count();


      $courseExamReportDTO->attendancePercentage = number_format((doubleval($registrationsHasAttended) / $total) * 100, 2);
     
      $courseExamReportDTO->averageScore =number_format( $registrations
      ->filter(fn ($r) => $r->hasAttended && $r->mark > 5)
      ->avg('mark') ?? 0.0,2) ;

      $report = $this->excelGeneratorService->generateCourseExamReport($courseExamReportDTO);
      return $report;
      
    }
}