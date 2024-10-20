<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('reportID'); // Primary key
            $table->foreignId('generatedBy')->constrained('users')->onDelete('cascade'); // Foreign key referencing users
            $table->decimal('totalPaid', 10, 2); // Total paid amount
            $table->decimal('totalUnpaid', 10, 2); // Total unpaid amount
            $table->integer('Year'); // Year of the report
            $table->string('forMonth'); // Month in name form (e.g., 'January')
            $table->decimal('percentPaid', 5, 2); // Percentage of fees paid
            $table->decimal('totalAmount', 10, 2); // Total amount of fees for the year
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
