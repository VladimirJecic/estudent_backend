<?php

namespace App\estudent\controller\model\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="indexNum", type="string", example="2020/0012"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="role", type="string", example="student"),
 *     @OA\Property(
 *         property="courses",
 *         type="array",
 *         @OA\Items(type="object")
 *     ),
 *     @OA\Property(
 *         property="examRegistrations",
 *         type="array",
 *         @OA\Items(type="object")
 *     ),
 *     @OA\Property(
 *         property="signedRegistrations",
 *         type="array",
 *         @OA\Items(type="object")
 *     )
 * )
 */
class UserResource extends JsonResource
{    


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
   public function toArray(Request $request): array
   {
         return $this->role == 'student' ?
            [
               'id'=> $this->id,
               'indexNum' => $this->indexNum,
               'name' => $this->name,
               'email' => $this->email,
               'role'=>'student',
               // 'courses'=>$this->courses ?? [],
               // 'examRegistrations'=>$this->examRegistrations ?? [],
            ]:
            [
               'id'=>$this->id,
               'indexNum' => $this->indexNum,
               'name' => $this->name,
               'email' => $this->email,
               'role'=>'admin',
               // 'courses'=>$this->courses ?? [],
               // 'signedRegistrations'=>$this->signedRegistrations ?? [],
            ];
   }

}

