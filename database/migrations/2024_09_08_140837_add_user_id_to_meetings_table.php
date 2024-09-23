<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToMeetingsTable extends Migration
{
    public function up()
    {
        Schema::table('meetings', function (Blueprint $table) {
            // Check if the column does not exist before adding it
            if (!Schema::hasColumn('meetings', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('adoption_request_id');

                // Add the foreign key constraint
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            // Drop the foreign key constraint if it exists
            if (Schema::hasColumn('meetings', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
}
