<?php

namespace App\estudent\controller;

use App\estudent\controller\model\resources\ExamPeriodResource;
use App\estudent\controller\model\requests\GetExamPeriodsRequest;
use App\estudent\domain\ports\input\ExamPeriodService;

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
    public function getExamPeriodsWithFilters(GetExamPeriodsRequest $request)
    {
        $examPeriodFilters = $request->toDto();
        $examPeriods = $this->examPeriodService->getAllExamPeriodsWithFilters($examPeriodFilters);
        return $this->createResponse(ExamPeriodResource::collection($examPeriods),
        'Exam periods retrieved successfully');
    }


}