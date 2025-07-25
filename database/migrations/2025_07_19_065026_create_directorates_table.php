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
        Schema::create('directorates', function (Blueprint $table) {
          $table->id();
          $table->string('name');
          $table->unsignedBigInteger('directorate_type_id');
          $table->foreign('directorate_type_id')->references('id')->on('directorate_types')
          ->onDelete('no action')
          ->onUpdate('cascade');
          $table->unsignedBigInteger('directorate_id')->nullable();
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directorates');
    }
};
