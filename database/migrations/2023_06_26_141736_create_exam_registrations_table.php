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
        Schema::create('exam_registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('exam_period_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('signed_by_id')->nullable();
            $table->integer('mark');
            $table->string('comment');
            $table->primary(['course_id', 'exam_period_id', 'student_id', 'id']);
            $table->foreign('course_id')->references('course_id')->on('course_exams')->onDelete('cascade');
            $table->foreign('exam_period_id')->references('exam_period_id')->on('course_exams')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('signed_by_id')->references('id')->on('users')->onDelete('set null');
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
