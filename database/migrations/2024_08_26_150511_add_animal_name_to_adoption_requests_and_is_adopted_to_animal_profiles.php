<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnimalNameToAdoptionRequestsAndIsAdoptedToAnimalProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->string('animal_name')->after('animal_id');
        });

        Schema::table('animal_profiles', function (Blueprint $table) {
            $table->boolean('is_adopted')->default(false);
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
            $table->dropColumn('animal_name');
        });

        Schema::table('animal_profiles', function (Blueprint $table) {
            $table->dropColumn('is_adopted');
        });
    }
}

