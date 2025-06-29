<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    // use Illuminate\Database\Eloquent\Model;
    use App\Models\CourseExam;
    use App\Models\User;


    class ExamRegistration extends CustomModel
    {
        use HasFactory;

        /**
         * Indicates if the IDs are auto-incrementing.
         *
         * @var bool
         */
        public $incrementing = false;
        protected $casts = [
            'course_id' => 'integer',
            'exam_period_id' => 'integer',
            'student_id' => 'integer',
        ];
        protected $primaryKey = ['course_id', 'exam_period_id','student_id'];
        protected $attributes = [
            'hasAttended' => false,
            'mark'=> 5,
            'comment' => '',
            'signed_by_id'=> NULL,
        ];  
        protected $fillable = [
            'mark',
            'comment',
            'signed_by_id',
        ];
        public function courseExam()
        {   
            return $this->belongsTo(CourseExam::class,['course_id','exam_period_id'],
            ['course_id','exam_period_id'])->with('course');
        }

        public function student()
        {
            return $this->belongsTo(User::class,'student_id','id');
        }

        public function signedBy()
        {
            return $this->belongsTo(User::class, 'signed_by_id','id');
        }
       
                
        
    }
