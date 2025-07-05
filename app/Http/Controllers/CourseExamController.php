<?php

namespace App\Http\Controllers;
use App\Models\CourseExam;
use App\Models\ExamPeriod;
use App\Models\ExamRegistration;
use Illuminate\Http\Request;
use App\Http\Resources\CourseExamResource;
use Validator;
use Carbon\Carbon;

class CourseExamController extends BaseController
{


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
        $query = CourseExam::with(['courseInstance', 'examPeriod']);

        // Filters
        if ($request->filled('course-name')) {
            $query->whereHas('courseInstance.course', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('course-name') . '%');
            });
        }

        if ($request->filled('date-from')) {
            $query->whereDate('examDateTime', '>=', Carbon::parse($request->input('date-from'))->toDateString());
        }
        
        if ($request->filled('date-to')) {
            $query->whereDate('examDateTime', '<=', Carbon::parse($request->input('date-to'))->toDateString());
        }

        $query->orderBy('examDateTime', 'desc');

        $pageSize = (int) $request->input('page-size', 10);
        $courseExams = $query->paginate($pageSize)->appends($request->query());

        return response()->json([
            'content' => CourseExamResource::collection($courseExams->items()),
            'totalPages' => $courseExams->lastPage(),
            'totalElements' => $courseExams->total(),
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
    public function getRemainingCourseExams(Request $request, $examPeriodId)
    {

        $result['courseExams'] = $this->getRemainingCourseExamsCollection($request, $examPeriodId);
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
    public function getRegisterableCourseExams(Request $request, $examPeriodId)
    {
        $student = auth()->user();
        $remainingCourseExams = $this->getRemainingCourseExamsCollection($request, $examPeriodId);
        $examRegistrationsForExamPeriod = ExamRegistration::where('student_id', $student->id)
        ->whereHas('courseExam', function ($query) use ($examPeriodId) {
            $query->where('exam_period_id', $examPeriodId);
        })->get();
        $registerableCourseExams  = $remainingCourseExams->reject(fn ($courseExam) =>
        in_array($courseExam->id,$examRegistrationsForExamPeriod->pluck('course_exam_id')->toArray())
    );
        $result['courseExams'] = CourseExamResource::collection($registerableCourseExams);    
        return $this->sendResponse($result, 'Registerable CourseExams retrieved successfully');
    }

    private function getRemainingCourseExamsCollection(Request $request, $examPeriodId)
    {
        $validator = Validator::make(
            ['examPeriodId' => $examPeriodId],
            [
                'examPeriodId' => ['required', 'integer', 'exists:exam_periods,id'],
            ]
        );
    
        if ($validator->fails()) {
            return $this->sendError(
                "Validation error: ExamPeriod with examPeriodId {$examPeriodId} not found",
                $validator->errors(),
                400
            );
        }

        $examPeriod = ExamPeriod::with('exams.courseInstance')->findOrFail($examPeriodId);
        
        $student = auth()->user();
        
        $enrolledCourses = $student->courseIntances()->pluck('id')->toArray();

        $courseExams_where_enrolled = $examPeriod->exams->reject(fn ($courseExam)=>
            !in_array($courseExam->courseInstance->id,$enrolledCourses)
        );

        $examAttempts = ExamRegistration::with('student','courseExam')->where('student_id', $student->id)->get();

        $successfulAttempts = $examAttempts->count() > 0 ? $examAttempts->whereBetween('mark', [6, 10])->pluck('courseExam.course_instance_id')->toArray() : [];

        $courseExams_remaining =  $courseExams_where_enrolled->reject(fn ($courseExam) => 
            in_array($courseExam->course_instance_id, $successfulAttempts)
        );
        return CourseExamResource::collection($courseExams_remaining);;
    }
   

}