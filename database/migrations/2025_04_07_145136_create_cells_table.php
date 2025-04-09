<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cells', function (Blueprint $table) {
            $table->id();
            $table->string('afdeling', 15); // Wing/section
            $table->string('celnummer', 4); // Cell number
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cells');
    }
};