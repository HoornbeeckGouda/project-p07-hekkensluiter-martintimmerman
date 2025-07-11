<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('antecedents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prisoner_id')->constrained('prisoners')->onDelete('cascade');
            $table->string('delict', 255)->nullable(); 
            $table->date('datum_delict')->nullable(); 
            $table->text('beschrijving')->nullable(); 
            $table->string('bewijsmateriaal')->nullable(); 
            $table->string('bewijsmateriaal_type', 50)->nullable(); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('antecedents');
    }
};