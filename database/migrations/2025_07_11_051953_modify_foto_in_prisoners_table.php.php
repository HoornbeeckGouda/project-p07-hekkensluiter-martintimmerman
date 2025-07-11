<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prisoners', function (Blueprint $table) {
            $table->string('foto', 255)->nullable()->change(); 
        });
    }

    public function down()
    {
        Schema::table('prisoners', function (Blueprint $table) {
            $table->binary('foto')->nullable()->change();
        });
    }
};