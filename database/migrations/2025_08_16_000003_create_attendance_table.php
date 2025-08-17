<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Faculty_ID');
            $table->unsignedBigInteger('class_schedule_ID')->nullable();
            $table->string('rfid_tag');
            $table->date('date');
            $table->timestamp('time_in')->nullable();
            $table->timestamp('time_out')->nullable();
            $table->enum('status', ['Present', 'Absent'])->default('Present');
            $table->timestamps();

            $table->foreign('Faculty_ID')->references('Faculty_ID')->on('faculty')->onDelete('cascade');
            $table->foreign('class_schedule_ID')->references('id')->on('class_schedules')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
