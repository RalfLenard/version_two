<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('adoption_requests', function (Blueprint $table) {
        $table->string('status')->default('Pending'); // Default status is "Pending"
    });
}

public function down()
{
    Schema::table('adoption_requests', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
