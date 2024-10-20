<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'classrooms';

    protected $primaryKey = 'classID'; // Specify the primary key

    protected $fillable = [
        'className',
        'year',
        'teacherID', // Add teacherID to the fillable array
    ];

    public $incrementing = false; // Indicate that classID is not auto-incrementing

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($classroom) {
            // Ensure that the classroom has a 'className' and 'year' before generating the classID
            if (!empty($classroom->className) && !empty($classroom->year)) {
                // Use the generateClassID method with the className and year
                $classroom->classID = (new self)->generateClassID($classroom->className, $classroom->year);
            }
        });
    }


    private function generateClassID($className, $year)
    {
        // Ensure the class name is at least 3 characters long
        $shortClassName = strtoupper(substr($className, 0, 3));

        // Ensure year is numeric and valid
        $yearNumber = (int) $year;

        if (strlen($shortClassName) < 3) {
            // If the class name is shorter than 3 characters, pad with X
            $shortClassName = str_pad($shortClassName, 3, 'X');
        }

        // Get the latest classroom for the same year and short class name
        $latestClassroom = Classroom::where('classID', 'LIKE', "{$yearNumber}{$shortClassName}%")
            ->orderBy('classID', 'desc')
            ->first();

        // Default to 1 if no classrooms exist
        $newIDNumber = 1;

        if ($latestClassroom) {
            // Extract the numeric part after the year and class name (4th character onwards)
            $latestIDNumber = (int) substr($latestClassroom->classID, 5); // Assuming format YYYCCC001

            // Increment by 1
            $newIDNumber = $latestIDNumber + 1;
        }

        // Ensure the new ID is formatted correctly (3 digits for the number part)
        return $yearNumber . $shortClassName . str_pad($newIDNumber, 3, '0', STR_PAD_LEFT);
    }



    // Define the relationship with the User model (assuming users are teachers)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacherID', 'id'); // Adjust 'id' if your User primary key is different
    }
}
