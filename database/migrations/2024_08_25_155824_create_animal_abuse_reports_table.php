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
         Schema::create('animal_abuse_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Reference to the user who reported
            $table->string('description')->nullable(); // Description of the abuse
            $table->string('photos1')->nullable(); // Store photo URLs or paths as JSON
            $table->string('photos2')->nullable();
            $table->string('photos3')->nullable();
            $table->string('photos4')->nullable();
            $table->string('photos5')->nullable();
            $table->string('videos1')->nullable(); // Store video URLs or paths as JSON
            $table->string('videos2')->nullable();
            $table->string('videos3')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_abuse_reports');
    }
};
