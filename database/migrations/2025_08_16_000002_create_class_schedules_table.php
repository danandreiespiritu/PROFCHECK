<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faculty_ID')->nullable();
            $table->string('subject');
            $table->string('section');
            $table->string('Yearlvl')->nullable();
            $table->string('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->timestamps();

            $table->foreign('faculty_ID')->references('Faculty_ID')->on('faculty')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
