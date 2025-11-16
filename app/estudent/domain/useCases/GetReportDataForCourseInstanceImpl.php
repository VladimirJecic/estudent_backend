<?php
namespace App\estudent\domain\useCases;

use App\estudent\domain\ports\input\GetReportDataForCourseInstance;
use App\estudent\domain\ports\input\model\CourseSemesterReportDTO;
use App\estudent\domain\ports\input\model\ExamPeriodMetricPointDTO;
use App\estudent\domain\model\CourseInstance;
use App\estudent\domain\model\ExamPeriod;
use App\estudent\domain\ports\input\CourseExamService;

class GetReportDataForCourseInstanceImpl implements GetReportDataForCourseInstance
{
    private readonly CourseExamService $courseExamService;
    public function __construct(CourseExamService $courseExamService)
    {
        $this->courseExamService = $courseExamService;
    }

    public function getReportDataForCourseInstance(int $courseInstanceId): CourseSemesterReportDTO
    {
        $courseInstance = CourseInstance::with([
            'course',
            'semester',
            'participants',
            'courseExams.examPeriod',
            'courseExams.examRegistrations'
        ])->findOrFail($courseInstanceId);

        $title = 'Izveštaj za predmet ' . $courseInstance->course->name . ' u školskoj ' . $courseInstance->semester->academic_year;
        $enrolledCount = $courseInstance->participants->count();

        $attendanceSeries = [];
        $passageSeries = [];
        $averageGradesSeries = [];
        $passedStudentIds = collect();

        foreach ($courseInstance->courseExams as $courseExam) {
            $examPeriod = $courseExam->examPeriod;
            $allRegistrations = $courseExam->examRegistrations;

            // Use service methods for calculations
            $attendancePercentage = $this->courseExamService->calculateAttendancePercentage($courseExam->id);
            $averageGrade = $this->courseExamService->calculateAverageScore($courseExam->id);
            $passagePercentage = $this->calculatePassagePercentage($allRegistrations, $enrolledCount, $passedStudentIds);

            $attendanceSeries[] = new ExamPeriodMetricPointDTO(
                $examPeriod->id,
                $examPeriod->name,
                $attendancePercentage
            );
            $passageSeries[] = new ExamPeriodMetricPointDTO(
                $examPeriod->id,
                $examPeriod->name,
                $passagePercentage
            );
            $averageGradesSeries[] = new ExamPeriodMetricPointDTO(
                $examPeriod->id,
                $examPeriod->name,
                $averageGrade
            );
        }

        // Sort all series by examPeriod dateStart
        $sortByDateStart = fn($a, $b) => $this->getExamPeriodDateStart($a) <=> $this->getExamPeriodDateStart($b);
        usort($attendanceSeries, $sortByDateStart);
        usort($passageSeries, $sortByDateStart);
        usort($averageGradesSeries, $sortByDateStart);

        $passedCount = $passedStudentIds->count();

        return new CourseSemesterReportDTO(
            $title,
            $attendanceSeries,
            $passageSeries,
            $averageGradesSeries,
            $enrolledCount,
            $passedCount
        );
    }

    private function calculatePassagePercentage($registrations, $enrolledCount, &$passedStudentIds): float
    {
        $passedInThisPeriod = $registrations
            ->filter(fn($r) => $r->hasAttended && $r->mark > 5)
            ->pluck('student_id')
            ->unique();
        $passedStudentIds = $passedStudentIds->merge($passedInThisPeriod)->unique();
        return $enrolledCount > 0
            ? round(($passedInThisPeriod->count() / $enrolledCount) * 100, 2)
            : 0.0;
    }

    private function getExamPeriodDateStart(ExamPeriodMetricPointDTO $dataPoint): string
    {
        $examPeriod = ExamPeriod::find($dataPoint->examPeriodId);
        return $examPeriod?->dateStart ?? '';
    }
}
