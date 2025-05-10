<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prisoner_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prisoner_id')->constrained('prisoners')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID of the guard who created the log
            $table->string('log_type', 50); // Type of log (e.g., 'recreation', 'visit', 'medical')
            $table->text('description'); // Detailed description of the log entry
            $table->dateTime('log_date'); // Date and time of the log entry
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prisoner_logs');
    }
};