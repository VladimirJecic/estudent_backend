<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('course_exams', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('exam_period_id');
            $table->primary(['course_id', 'exam_period_id']);
            $table->dateTime('examDateTime');
            $table->string('hall');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('exam_period_id')->references('id')->on('exam_periods')->onDelete('cascade');
            $table->timestamps();
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
