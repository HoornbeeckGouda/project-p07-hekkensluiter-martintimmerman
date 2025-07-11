<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorLogsTable extends Migration
{
    public function up()
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_name');
            $table->string('id_document_type')->nullable();
            $table->string('id_document_number');
            $table->dateTime('arrival_time');
            $table->dateTime('departure_time')->nullable();
            $table->string('visit_purpose')->nullable(); 
            $table->foreignId('prisoner_id')->nullable()->constrained('prisoners')->onDelete('set null'); 
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitor_logs');
    }
}