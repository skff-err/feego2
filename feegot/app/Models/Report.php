<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural of the model name
    protected $table = 'reports';

    // Define the fillable attributes
    protected $fillable = [
        'generatedBy',
        'totalPaid',
        'totalUnpaid',
        'Year',
        'forMonth',
        'percentPaid',
        'totalAmount',
    ];

    // Define any relationships, if necessary
    public function user()
    {
        return $this->belongsTo(User::class, 'generatedBy'); // Relationship to the User model
    }
}
