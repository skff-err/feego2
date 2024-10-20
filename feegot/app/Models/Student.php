<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students'; // Specify the table name if it's not pluralized automatically
    protected $primaryKey = 'IdentityCardNumber'; // Set the primary key field
    public $incrementing = false; // Since it's a string, set incrementing to false
    protected $keyType = 'string';

    // Define fillable attributes to allow mass assignment
    protected $fillable = [
        'IdentityCardNumber', // Ensure this matches your migration definition
        'Name',
        'ClassID',
        'GuardianID',
        'Year', // If you have a year attribute
    ];

    // Optionally define relationships if necessary
    public function guardian()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classID');
    }
}
