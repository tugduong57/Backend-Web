<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThesesTable extends Migration
{
    public function up()
    {
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('program', 255);
            $table->string('supervisor', 100);
            $table->text('abstract');
            $table->date('submission_date')->nullable();
            $table->date('defense_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('theses');
    }
}
