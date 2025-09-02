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
            'data'=>$data,
            'message'=>$message
        ];
        return response()->json($response,$code);
        
    }
    public function sendError($data=null,$error_message='',$code = 404){
        $response = [
            'success'=>false,
            'data'=>$data ? $data : null,   
            'message'=>$error_message
        ];
      
        return response()->json($response,$code);
    }

}
