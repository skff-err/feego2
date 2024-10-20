<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Specify the table if it's not the plural form of the model name
    protected $table = 'payments';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'PaymentID';

    // Allow mass assignment for the following attributes
    protected $fillable = [
        'IdentityCardNumber',
        'FeeID',
        'Method',
        'Amount',
        'TeacherAppr',
        'AdminAppr',
        'Status',
    ];

    // Define relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'IdentityCardNumber', 'IdentityCardNumber');
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class, 'FeeID', 'FeeID');
    }
}
