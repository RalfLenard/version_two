<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminIdToAdoptionRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->after('reason'); // Adjust the position as needed
        });
    }

    public function down()
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn('admin_id');
        });
    }
}
