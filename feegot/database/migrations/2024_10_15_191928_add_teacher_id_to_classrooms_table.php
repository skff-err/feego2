<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeacherIdToClassroomsTable extends Migration
{
    public function up()
    {
        Schema::table('classrooms', function (Blueprint $table) {
            // Add the teacherID column and set up the foreign key relationship
            $table->unsignedBigInteger('teacherID')->nullable(); // Make it nullable if some classrooms don’t have assigned teachers yet
            $table->foreign('teacherID')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('classrooms', function (Blueprint $table) {
            // Drop the foreign key and the column
            $table->dropForeign(['teacherID']);
            $table->dropColumn('teacherID');
        });
    }
}
