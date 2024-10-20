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
            $table->decimal('amount', 10, 2)->default(0)->change(); // Example of setting a default
        });
    }

    public function down()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->nullable()->change(); // Change back to nullable
        });
    }
};
