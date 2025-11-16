<?php
 namespace App\estudent\domain\useCases;
 use App\estudent\domain\ports\input\GetReportForCourseExam;
use App\estudent\domain\model\CourseExam;
use App\estudent\domain\model\ExamRegistration;
use App\estudent\domain\ports\output\model\CourseExamReportDTO;
use App\estudent\domain\ports\output\model\CourseExamReportItemDTO;
use App\estudent\domain\ports\output\GenerateCourseExamReport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\estudent\domain\ports\input\CourseExamService;

 class GetReportForCourseExamImpl implements GetReportForCourseExam{

    private readonly GenerateCourseExamReport $excelGeneratorService;
    private readonly CourseExamService $courseExamService;

    public function __construct(GenerateCourseExamReport $excelGeneratorService, CourseExamService $courseExamService)
    {
        $this->excelGeneratorService = $excelGeneratorService;
        $this->courseExamService = $courseExamService;
    }

    public function getReportForCourseExam(int $courseExamId): BinaryFileResponse
    {

        $courseExam = CourseExam::with(['courseInstance.course','examRegistrations.student'])
        ->where('id', $courseExamId)
        ->firstOrFail();
    /** @var \App\estudent\domain\model\CourseExam $courseExam */
    $courseExamReportDTO = new CourseExamReportDTO($courseExam);
    $courseExamReportDTO->reportItemList = array_map(fn(ExamRegistration $examRegistration)=> new CourseExamReportItemDTO($examRegistration)
    ,$courseExam->examRegistrations->all());

    $courseExamReportDTO->attendancePercentage = $this->courseExamService->calculateAttendancePercentage($courseExamId);
    $courseExamReportDTO->averageScore = $this->courseExamService->calculateAverageScore($courseExamId);
    $report = $this->excelGeneratorService->generateCourseExamReport($courseExamReportDTO);
    return $report;

    }
    
 }