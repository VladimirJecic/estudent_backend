<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{    
    private $courses;
    private $examRegistrations;
    private $signedRegistrations;
    
    /**
    * UserResource constructor.
    *
    * @param mixed $resource
    * @param mixed $courses
    * @param mixed $examRegistrations
    * @param mixed $signedRegistrations
    */
    public function __construct($resource, $courses = null,$examRegistrations = null,$signedRegistrations = null)
    {
        parent::__construct($resource);
        $this->courses = $courses;
        $this->examRegistrations  = $examRegistrations;
        $this->signedRegistrations = $signedRegistrations;
    }
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
