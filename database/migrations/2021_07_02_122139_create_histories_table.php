<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->increments('hisID');
            $table->string('hisDesc')->nullable();
            $table->integer('memID')->unsigned()->nullable();
            $table->integer('userID')->unsigned()->nullable();
            $table->integer('issueID')->unsigned()->nullable();
            $table->integer('old_workflow')->unsigned()->nullable();
            $table->integer('new_workflow')->unsigned()->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
