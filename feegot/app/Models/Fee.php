<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $table = 'fees';
    protected $primaryKey = 'FeeID'; // Specify the primary key

    protected $fillable = [
        'Details',
        'FeeID',
        'Amount',
        'DueDate',
        'ClassID',
        'Year',
        'global', // Add global to fillable
    ];

    protected $dates = [
        'DueDate',
    ];

    public $incrementing = false; // Indicate that FeeID is not auto-incrementing

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($fee) {
            $fee->FeeID = self::generateFeeID();

            // If global is true, set ClassID to 'N/A'
            if ($fee->global) {
                $fee->ClassID = 'N/A';
            }
        });

        static::updating(function ($fee) {
            // Apply the same logic during updates
            if ($fee->global) {
                $fee->ClassID = 'N/A';
            }
        });
    }

    private static function generateFeeID()
    {
        // Get the latest fee with a FeeID
        $latestFee = self::orderBy('FeeID', 'desc')->first();

        // Default to 1 if no fees exist
        $newIDNumber = 1;

        if ($latestFee) {
            // Extract the numeric part of the last FeeID
            $latestID = (int) substr($latestFee->FeeID, 2); // Remove "F-" and convert to integer
            
            // Increment by 1
            $newIDNumber = $latestID + 1;
        }

        // Ensure new ID is formatted as F-00001 (5 digits)
        return 'F-' . str_pad($newIDNumber, 5, '0', STR_PAD_LEFT);
    }
}
