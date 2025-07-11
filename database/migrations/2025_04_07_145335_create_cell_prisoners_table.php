<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cell_prisoners', function (Blueprint $table) {
            $table->id();
            $table->date('datum_start'); // Start date
            $table->date('datum_eind')->nullable(); // End date
            $table->time('tijd_start'); // Start time
            $table->time('tijd_eind')->nullable(); // End time
            $table->foreignId('prisoner_id')->constrained('prisoners');
            $table->foreignId('cell_id')->constrained('cells');
            $table->text('verslag_bewaker')->nullable(); // Guard report
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cell_prisoners');
    }
};