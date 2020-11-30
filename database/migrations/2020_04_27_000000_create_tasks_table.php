<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_on')->nullable();
            $table->date('due_on')->nullable();
            $table->date('completed_on')->nullable();
            $table->integer('estimate_hour')->nullable();
            $table->string('status')->default('created');
            $table->integer('user_id');
            $table->integer('project_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
