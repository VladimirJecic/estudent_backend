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
        Schema::create('course_exams', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('course_instance_id');
            $table->unsignedBigInteger('exam_period_id');
            $table->dateTime('examDateTime');
            $table->string('hall');
            $table->timestamps();
        
            $table->foreign('course_instance_id')->references('id')->on('course_instances')->noActionOnDelete();
            $table->foreign('exam_period_id')->references('id')->on('exam_periods')->cascadeOnDelete();
        
            $table->unique(['course_instance_id', 'exam_period_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_exams');
    }
};
