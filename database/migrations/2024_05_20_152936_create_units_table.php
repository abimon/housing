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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appartment_id');
            $table->string('unit_label');
            $table->unsignedBigInteger('occupant_id')->nullable();
            $table->string('status')->default('Vacant');
            $table->timestamps();

            $table->foreign('occupant_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('appartment_id')->references('id')->on('appartments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
