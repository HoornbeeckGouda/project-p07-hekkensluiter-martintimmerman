<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prisoner_logs', function (Blueprint $table) {
            $table->string('bijlage')->nullable()->after('description'); 
            $table->string('bijlage_type', 50)->nullable()->after('bijlage'); 
        });
    }

    public function down()
    {
        Schema::table('prisoner_logs', function (Blueprint $table) {
            $table->dropColumn(['bijlage', 'bijlage_type']);
        });
    }
};