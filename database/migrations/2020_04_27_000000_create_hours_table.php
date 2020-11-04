<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoursTable extends Migration
{
    public function up()
    {
        Schema::create('hours', function (Blueprint $table) {
            $table->id();
            $table->integer('hours')->nullable();
            $table->text('description')->nullable();
            $table->date('worked_on')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('task_id')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hours');
    }
}
