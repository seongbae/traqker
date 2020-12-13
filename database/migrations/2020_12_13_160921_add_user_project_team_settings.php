<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserProjectTeamSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('settings');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->text('settings');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->text('settings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn(['settings']);
        });

        Schema::table('projects', function ($table) {
            $table->dropColumn(['settings']);
        });

        Schema::table('teams', function ($table) {
            $table->dropColumn(['settings']);
        });
    }
}
