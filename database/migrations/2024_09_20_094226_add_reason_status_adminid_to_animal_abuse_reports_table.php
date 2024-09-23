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
        Schema::table('animal_abuse_reports', function (Blueprint $table) {
            // Only add 'reason' column if it doesn't exist
            if (!Schema::hasColumn('animal_abuse_reports', 'reason')) {
                $table->text('reason'); // 'reason' is required
            }

            // Only add 'status' column if it doesn't exist
            if (!Schema::hasColumn('animal_abuse_reports', 'status')) {
                $table->string('status')->default('pending'); // 'status' column, defaulting to 'pending'
            }

            // Only add 'admin_id' column if it doesn't exist
            if (!Schema::hasColumn('animal_abuse_reports', 'admin_id')) {
                $table->unsignedBigInteger('admin_id')->nullable(); // 'admin_id' column
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('animal_abuse_reports', function (Blueprint $table) {
            if (Schema::hasColumn('animal_abuse_reports', 'reason')) {
                $table->dropColumn('reason');
            }

            if (Schema::hasColumn('animal_abuse_reports', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('animal_abuse_reports', 'admin_id')) {
                $table->dropColumn('admin_id');
            }
        });
    }
};
