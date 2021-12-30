<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('notiID');
            $table->integer('notiTypeID')->unsigned()->nullable();
            $table->integer('userID')->unsigned()->nullable();
            $table->integer('latitude')->unsigned()->nullable();
            $table->string('notiUrl', 100)->nullable();
            $table->integer('created_by')->unsigned()->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
