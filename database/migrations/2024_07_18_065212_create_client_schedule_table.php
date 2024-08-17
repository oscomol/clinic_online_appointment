<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_schedule', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('doctorsId');
            $table->unsignedInteger('userId');
            $table->string('date');
            $table->string('expectedTime');
            $table->unsignedInteger('number');
            $table->unsignedInteger('age');
            $table->string('patientName');
            $table->string('gender');
            $table->string('address');
            $table->string('concern');
            $table->string('severity');
            $table->unsignedInteger('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_schedule');
    }
};
