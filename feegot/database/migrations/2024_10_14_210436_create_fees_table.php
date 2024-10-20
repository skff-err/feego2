<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesTable extends Migration
{
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->string('FeeID')->primary(); // F-<5 digits>
            $table->decimal('Amount', 8, 2);
            $table->date('DueDate');
            $table->string('ClassID'); // Foreign key reference to the classrooms
            $table->integer('Year'); // Year (1-6)
            $table->text('Details'); // Required details field
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fees');
    }
}
