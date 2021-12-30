<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function(Blueprint $table) {
            $table->foreign('infoID')->references('infoID')->on('information');
        });

        Schema::table('projects', function(Blueprint $table) {
            $table->foreign('statusID')->references('statusID')->on('project_statuses');
            $table->foreign('created_by')->references('userID')->on('accounts');
            $table->foreign('dateID')->references('dateID')->on('dates');
        });

        Schema::table('members', function(Blueprint $table) {
            $table->foreign('userID')->references('userID')->on('accounts');
            $table->foreign('proID')->references('proID')->on('projects');
            $table->foreign('roleID')->references('roleID')->on('roles');
        });

        Schema::table('comments', function(Blueprint $table) {
            $table->foreign('memID')->references('memID')->on('members');
            $table->foreign('issueID')->references('issueID')->on('issues');
        });

        Schema::table('issues', function(Blueprint $table) {
            $table->foreign('typeID')->references('typeID')->on('issue_types');
            $table->foreign('priorityID')->references('priorityID')->on('priorities');
            $table->foreign('proID')->references('proID')->on('projects');
            $table->foreign('memID')->references('memID')->on('members');
            $table->foreign('workflowID')->references('workflowID')->on('workflows');
            $table->foreign('dateID')->references('dateID')->on('dates');
        });

        Schema::table('histories', function(Blueprint $table) {
            $table->foreign('memID')->references('memID')->on('members');
            $table->foreign('userID')->references('userID')->on('accounts');
            $table->foreign('issueID')->references('issueID')->on('issues');
            $table->foreign('old_workflow')->references('workflowID')->on('workflows');
            $table->foreign('new_workflow')->references('workflowID')->on('workflows');
        });

        Schema::table('access', function(Blueprint $table) {
            $table->foreign('proID')->references('proID')->on('projects');
            $table->foreign('issueID')->references('issueID')->on('issues');
        });
        Schema::table('logworks', function(Blueprint $table) {
            $table->foreign('locationID')->references('locationID')->on('locations');
            $table->foreign('issueID')->references('issueID')->on('issues');
        });
        Schema::table('notifications', function(Blueprint $table) {
            $table->foreign('created_by')->references('userID')->on('accounts');
            $table->foreign('notiTypeID')->references('notiTypeID')->on('notification_types');
        });
        Schema::table('upload_files', function(Blueprint $table) {
            $table->foreign('cmtID')->references('cmtID')->on('comments');
        });
        Schema::table('viewers', function(Blueprint $table) {
            $table->foreign('memID')->references('memID')->on('members');
            $table->foreign('issueID')->references('issueID')->on('issues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

