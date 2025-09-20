<?php

namespace App\estudent\controller\model\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
               'courses'=>$this->courses ?? [],
               'examRegistrations'=>$this->examRegistrations ?? [],
            ]:
            [
               'id'=>$this->id,
               'indexNum' => $this->indexNum,
               'name' => $this->name,
               'email' => $this->email,
               'role'=>'admin',
               'courses'=>$this->courses ?? [],
               'signedRegistrations'=>$this->signedRegistrations ?? [],
            ];
   }

}

