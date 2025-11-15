<?php

namespace App\estudent\controller;

use App\estudent\domain\model\Semester;
use App\estudent\controller\model\resources\SemesterResource;
use Illuminate\Http\Request;

class SemesterController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/admin/semesters",
     *     tags={"Admin Routes"},
     *     summary="Get all semesters",
     *     operationId="getSemesters",
     *     security={
     *             {"passport": {*}}
     *      },
     *     @OA\Response(
     *         response=200,
     *         description="List of all semesters"
     *     )
     * )
    */
    public function getSemesters()
    {
        $semesters = Semester::all();
        return $this->createResponse(SemesterResource::collection($semesters));
    }
}
