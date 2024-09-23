<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adoption_request_id');
            $table->dateTime('meeting_date');
            $table->string('status')->default('Scheduled');
            $table->timestamps();

            $table->foreign('adoption_request_id')
                  ->references('id')
                  ->on('adoption_requests')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
