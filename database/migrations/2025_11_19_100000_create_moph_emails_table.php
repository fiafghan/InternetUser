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
        Schema::create('moph_emails', function (Blueprint $table) {
            $table->id();
            $table->string('moph_id')->unique();
            $table->unsignedBigInteger('directorate_id');
            $table->string('email')->unique();
            $table->timestamps();

            $table->foreign('directorate_id')->references('id')->on('directorates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moph_emails');
    }
};
