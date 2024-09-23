<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReasonToAdoptionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->text('reason'); // The reason is required, no nullable() modifier.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }
}
