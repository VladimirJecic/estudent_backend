<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_registrations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('course_exam_id'); 
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('signed_by_id')->nullable();
        
            $table->boolean('hasAttended');
            $table->integer('mark');
            $table->string('comment');
        
            $table->unique(['course_exam_id', 'student_id']);
        
            $table->foreign('course_exam_id')->references('id')->on('course_exams')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('signed_by_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('no action');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_registrations');
    }
};
