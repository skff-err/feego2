<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('PaymentID'); // Primary Key
            $table->string('IdentityCardNumber'); // Define IdentityCardNumber column first
            $table->string('FeeID'); // FeeID column

            // Foreign Key Constraints
            $table->foreign('IdentityCardNumber')->references('IdentityCardNumber')->on('students')->onDelete('cascade'); // Foreign Key from students table
            $table->foreign('FeeID')->references('FeeID')->on('fees')->onDelete('cascade'); // Foreign Key from fees table

            // Other Columns
            $table->string('Method'); // Payment method
            $table->decimal('Amount', 10, 2); // Payment amount
            $table->enum('TeacherAppr', ['approved', 'pending', 'denied']); // Teacher approval status
            $table->enum('AdminAppr', ['approved', 'pending', 'denied']); // Admin approval status
            $table->enum('Status', ['verified', 'pending', 'denied']); // Payment status

            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
