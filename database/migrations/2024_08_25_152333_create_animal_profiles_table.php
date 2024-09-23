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
        Schema::create('animal_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the animal
            $table->string('profile_picture')->nullable(); // Path to the profile picture of the animal
            $table->text('description')->nullable(); // Description of the animal
            $table->integer('age'); // Age of the animal
            $table->text('medical_records')->nullable(); // Medical records of the animal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_profiles');
    }
};
