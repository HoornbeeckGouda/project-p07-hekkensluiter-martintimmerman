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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->string('log_type', 50); 
            $table->text('description'); 
            $table->dateTime('log_date'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prisoner_logs');
    }
};