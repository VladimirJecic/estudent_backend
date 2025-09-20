<?php

namespace App\estudent\controller;

use App\estudent\controller\model\resources\ExamPeriodResource;
use App\estudent\domain\ports\input\ExamPeriodService;
use Illuminate\Http\Request;
class ExamPeriodController extends BaseController
{

    private  readonly ExamPeriodService $examPeriodService; 

    public function __construct(ExamPeriodService $examPeriodService)
    {
        $this->examPeriodService = $examPeriodService;

    }

     /**
     * @OA\Get(
     *     path="/exam-periods",
     *     tags={"Common Routes"},
     *     summary="Retrieve only currently active or all exam periods",
     *     security={
     *              {"passport": {*}}
     *      },
    *   @OA\Parameter(
    *      name="only-active",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="boolean"
     *      )
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="[Active]ExamPeriods retrieved successfully",
     *    
     *     ),
     * )
     */
    public function getExamPeriods(Request $request)
    {
        $onlyActive = filter_var($request->query('onlyActive', false), FILTER_VALIDATE_BOOLEAN);
        $examPeriods = $this->examPeriodService->getAllExamPeriods($onlyActive);
        return $this->createResponse(ExamPeriodResource::collection($examPeriods),
        'Exam periods retrieved successfully');
    }


}