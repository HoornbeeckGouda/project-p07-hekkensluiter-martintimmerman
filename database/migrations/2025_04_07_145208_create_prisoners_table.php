<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prisoners', function (Blueprint $table) {
            $table->id();
            $table->string('roepnaam', 45)->nullable(); // First name
            $table->string('tussenvoegsel', 10)->nullable(); // Middle name
            $table->string('achternaam', 45); // Last name
            $table->string('straat', 150)->nullable(); // Street
            $table->string('huisnummer', 3)->nullable(); // House number
            $table->string('toevoeging', 10)->nullable(); // Addition
            $table->string('postcode', 6)->nullable(); // Postal code
            $table->string('woonplaats', 100)->nullable(); // City
            $table->string('bsn', 9)->nullable(); // Dutch SSN
            $table->string('delict', 255)->nullable(); // Offense
            $table->binary('foto')->nullable(); // Photo
            $table->date('geboortedatum')->nullable(); // Birth date
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prisoners');
    }
};