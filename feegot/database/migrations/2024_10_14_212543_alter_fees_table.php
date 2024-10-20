<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->date('dueDate')->nullable()->change(); // Example of making it nullable
        });
    }

    public function down()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->date('dueDate')->nullable(false)->change(); // Change back if needed
        });
    }
};
