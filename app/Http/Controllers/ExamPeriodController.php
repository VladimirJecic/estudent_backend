<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExamPeriodResource;
use App\Models\ExamPeriod;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class ExamPeriodController extends BaseController
{

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
        $onlyActive = isset($_GET['onlyActive']) ? $_GET['onlyActive'] : false;
        if($onlyActive === 'true')
            return  $this->active();
        else{
            return $this->all();
        }
    }
    public function active()
    {
        $currentDate = Carbon::now();
        $examPeriods = ExamPeriod::with('exams')->where('dateRegisterStart', '<=', $currentDate)
        ->where('dateEnd', '>=', $currentDate)
        ->get();
        return $this->sendResponse(ExamPeriodResource::collection($examPeriods),
        'Active ExamPeriods retrieved successfully');
    }
    public function all(){
         $examPeriods = ExamPeriod::with('exams')->get();
        return $this->sendResponse(ExamPeriodResource::collection($examPeriods),
        'ExamPeriods retrieved successfully');
    }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'dateStart' => 'required|date',
    //         'dateEnd' => 'required|date|after:dateStart',
    //         'name' => 'required|string',
    //     ]);

    //     if($validator->fails()){
    //         return $this->sendError('Validation error.',$validator->errors(),400);
    //     }
    //     $examPeriod = ExamPeriod::create([
    //         'dateStart' => $request->dateStart,
    //         'dateEnd' => $request->dateEnd,
    //         'name' => $request->name,
    //     ]);

    //     return $this->sendResponse(message:'ExamPeriod is stored successfully.');
        

    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Request $request, ExamPeriod $examPeriod)
    // {
    //     $url_segments_array= explode('/',$request->getPathInfo());
    //     $id = end($url_segments_array);
    //    $examPeriod = examPeriod::find($id);
    //    if(is_null($examPeriod)){
    //        return $this->sendError('examPeriod not found.');
    //    }
       
    //    $examPeriod->delete();
    //    return $this->sendResponse(message:'examPeriod deleted successfully');  
    // }
}