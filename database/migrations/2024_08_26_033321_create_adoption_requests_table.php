<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdoptionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adoption_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('animal_id');
            $table->unsignedBigInteger('user_id');  // Add this line
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('phone_number');
            $table->string('address');
            $table->decimal('salary', 8, 2);
            $table->text('question1');
            $table->text('question2');
            $table->text('question3');
            $table->string('valid_id');
            $table->string('valid_id_with_owner');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('animal_id')->references('id')->on('animal_profiles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');  // Add this line
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adoption_requests');
    }
}

