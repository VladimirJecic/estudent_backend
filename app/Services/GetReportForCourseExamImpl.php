<?php
 namespace App\Services;
 use App\Contracts\input\GetReportForCourseExam;
use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Collection;
use App\Models\CourseExam;
use App\Models\ExamRegistration;
use App\Contracts\output\model\CourseExamReportDTO;
use App\Contracts\output\model\CourseExamReportItemDTO;
use App\Contracts\output\GenerateCourseExamReport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

 class GetReportForCourseExamImpl implements GetReportForCourseExam{

    private readonly GenerateCourseExamReport $excelGeneratorService;

    public function __construct(GenerateCourseExamReport $excelGeneratorService)
    {
        $this->excelGeneratorService = $excelGeneratorService;
    }

    public function getReportForCourseExam(int $courseExamId): BinaryFileResponse
    {

        $courseExam = CourseExam::with('examRegistrations.student')
        ->where('id', $courseExamId)
        ->firstOrFail();
    /** @var \App\Models\CourseExam $courseExam */
    $courseExamReportDTO = new CourseExamReportDTO($courseExam);
    $courseExamReportDTO->reportItemList = array_map(fn(ExamRegistration $examRegistration)=> new CourseExamReportItemDTO($examRegistration)
    ,$courseExam->examRegistrations->all());

   if(count( $courseExamReportDTO->reportItemList) == 0)
    {
      throw new NotFoundException('There are no registrations for this course exam.');
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