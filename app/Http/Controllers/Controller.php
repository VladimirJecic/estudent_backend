<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 *  * @OA\Info(    
 *          title="Estudent",
 *           version="1.0")
 *  * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Rest API Server"
 * )
 */
class Controller extends BaseController
{
use AuthorizesRequests, ValidatesRequests;
}
