<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BaseController extends Controller
{
    //
    public function sendResponse($data=[],$message='',$code = 200){
        $response = [
            'success'=> true,
            'message'=>$message,
            'data'=>$data
        ];
        return response()->json($response,$code);
        
    }
    public function sendError($data=null,$error_message='',$code = 404){
        $response = [
            'success'=>false,
            'message'=>$error_message,
            'data'=>$data ? $data : null,
        ];
      
        return response()->json($response,$code);
    }

}
