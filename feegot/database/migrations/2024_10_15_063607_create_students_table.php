<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->string('IdentityCardNumber')->primary(); // Primary key, string type (for ID number)
            $table->string('Name'); // Name of the student
            $table->string('ClassID'); // Foreign key for class
            $table->unsignedBigInteger('GuardianID'); // Foreign key for guardian
            $table->integer('Year')->nullable(); // Add the year column (nullable)
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('ClassID')->references('classID')->on('classrooms')->onDelete('cascade');
            $table->foreign('GuardianID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
