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
     * Display a listing of the resource.
     */
    public function index()
    {
        $examPeriods = ExamPeriod::with('exams')->get();
        return $this->sendResponse(ExamPeriodResource::collection($examPeriods),
        'ExamPeriods retrieved successfully');
    }
    public function active()
    {
        $currentDate = Carbon::now();
        $examPeriods = ExamPeriod::where('dateRegisterStart', '<=', $currentDate)
        ->where('dateEnd', '>=', $currentDate)
        ->get();
        return $this->sendResponse(ExamPeriodResource::collection($examPeriods),
        'Active ExamPeriods retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dateStart' => 'required|date',
            'dateEnd' => 'required|date|after:dateStart',
            'name' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation error.',$validator->errors(),400);
        }
        $examPeriod = ExamPeriod::create([
            'dateStart' => $request->dateStart,
            'dateEnd' => $request->dateEnd,
            'name' => $request->name,
        ]);

        return $this->sendResponse(message:'ExamPeriod is stored successfully.');
        

    }

    /**
     * Display the specified resource.
     */
    // public function show(ExamPeriod $examPeriod)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(ExamPeriod $examPeriod)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, ExamPeriod $examPeriod)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ExamPeriod $examPeriod)
    {
        $url_segments_array= explode('/',$request->getPathInfo());
        $id = end($url_segments_array);
       $examPeriod = examPeriod::find($id);
       if(is_null($examPeriod)){
           return $this->sendError('examPeriod not found.');
       }
       
       $examPeriod->delete();
       return $this->sendResponse(message:'examPeriod deleted successfully');  
    }
}