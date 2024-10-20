<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDueDateTypeInFeesTable extends Migration
{
    public function up()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->date('DueDate')->change(); // Change DueDate to DATE type
        });
    }

    public function down()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->string('DueDate')->change(); // Change it back to string if needed
        });
    }
}
