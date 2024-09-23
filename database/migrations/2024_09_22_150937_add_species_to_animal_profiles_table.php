<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpeciesToAnimalProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animal_profiles', function (Blueprint $table) {
            $table->string('species')->nullable(); // Add species column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animal_profiles', function (Blueprint $table) {
            $table->dropColumn('species'); // Drop species column if rolled back
        });
    }
}

