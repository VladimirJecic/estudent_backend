<?php

namespace App\estudent\controller;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as PHPController;

/**
 *  * @OA\Info(    
 *          title="Estudent",
 *           version="1.0")
 *  * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Rest API Server"
 * )
 */
class BaseController extends PHPController
{
use AuthorizesRequests, ValidatesRequests;

    public function createResponse($data=[],$message='',$code = 200){
        $response = [
            'success'=> true,
            'message'=>$message,
            'data'=>$data
        ];
        return response()->json($response,$code);
        
    }
    public function createErrorResponse($data=null,$error_message='',$code = 404){
        $response = [
            'success'=>false,
            'message'=>$error_message,
            'data'=>$data ? $data : null,
        ];
      
        return response()->json($response,$code);
    }



}
