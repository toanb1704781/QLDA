<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->increments('issueID');
            $table->char('issueKey', 50)->nullable();
            $table->integer('typeID')->unsigned()->nullable();
            $table->integer('priorityID')->unsigned()->nullable();
            $table->integer('proID')->unsigned()->nullable();
            $table->integer('memID')->unsigned()->nullable();
            $table->integer('workflowID')->unsigned()->nullable();
            $table->integer('dateID')->unsigned()->nullable();
            $table->string('issueName')->nullable();
            $table->string('summary')->nullable();
            $table->text('issueDesc')->nullable();
            $table->string('attach')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('reporter')->unsigned()->nullable();
            $table->char('original_estimate', 5)->nullable();
            $table->timestamp('ola')->useCurrent();
            $table->timestamp('sla')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
