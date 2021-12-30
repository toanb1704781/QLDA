<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logworks', function (Blueprint $table) {
            $table->increments('logworkID');
            $table->integer('locationID')->unsigned()->nullable();
            $table->integer('issueID')->unsigned()->nullable();
            $table->string('workingTime')->nullable();
            $table->integer('timeRemaining')->unsigned()->nullable();
            $table->text('logworkNote')->nullable();
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
        Schema::dropIfExists('logworks');
    }
}
