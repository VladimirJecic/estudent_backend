<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExamPeriodResource;
use App\Contracts\input\ExamPeriodService;
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
     *      name="onlyActive",
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
    public function index(Request $request)
    {
        $onlyActive = filter_var($request->query('onlyActive', false), FILTER_VALIDATE_BOOLEAN);
        $examPeriods = $this->examPeriodService->getAllExamPeriods($onlyActive);
        return $this->sendResponse(ExamPeriodResource::collection($examPeriods),
        'Exam periods retrieved successfully');
    }


}