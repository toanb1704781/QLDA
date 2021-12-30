<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('proID');
            $table->char('proKey', 50)->nullable();
            $table->integer('dateID')->unsigned()->nullable();
            $table->integer('statusID')->unsigned()->nullable();
            $table->string('proName')->nullable();
            $table->text('proDesc')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('default_assignee')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
