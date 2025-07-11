<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cell_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prisoner_id')->constrained('prisoners');
            $table->foreignId('from_cell_id')->nullable()->constrained('cells');
            $table->foreignId('to_cell_id')->nullable()->constrained('cells');
            $table->date('datum_start'); // Start date
            $table->date('datum_eind')->nullable(); // End date
            $table->text('reden')->nullable(); // Reason
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cell_movements');
    }

};