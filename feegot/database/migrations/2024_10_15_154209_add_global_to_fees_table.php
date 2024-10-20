<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGlobalToFeesTable extends Migration
{
    public function up()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->boolean('global')->default(false); // New boolean column with default false
        });
    }

    public function down()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn('global'); // Drop the column if the migration is rolled back
        });
    }
}
