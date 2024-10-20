<?php

namespace App\Http\Controllers;

use App\Models\Fee; // Make sure to import the Fee model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $fees = Fee::all(); // Fetch all fees
        return view('fee.index', compact('fees', 'user')); // Return index view with fees
    }

    public function create()
    {
        return view('fee.create'); // Return the view to create a new fee
    }

    public function store(Request $request)
    {
        $request->validate([
            'details' => 'required',
            'amount' => 'required|numeric|min:0', // Validate that amount is numeric and non-negative
            'dueDate' => 'required|date', // Validate that dueDate is a valid date
            'classID' => 'string|regex:/^CL\d{3}$/', // Validate that classID is in the correct format (CLXXX)
            'year' => 'required|integer|in:1,2,3,4,5,6', // Validate year must be between 1-6
        ]);

        $feeData = [
            'Details' => $request->details,
            'Amount' => $request->amount,
            'DueDate' => $request->dueDate,
            'ClassID' => $request->classID,
            'Year' => $request->year,
            'global' => $request->has('global') ? 1 : 0, // Set global to true if checked, otherwise false
        ];

        try {
            Fee::create($feeData); // Attempt to create a new fee entry
            return redirect('/fees')->with('success', 'Fee created successfully.'); // Redirect with success message
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create fee: ' . $e->getMessage()])->withInput(); // Redirect back with error message
        }
    }

    public function update(Request $request, $FeeID)
    {
        $fee = Fee::where('FeeID', $FeeID)->firstOrFail(); // Change from find to where

        $request->validate([
            'details' => 'required',
            'amount' => 'required|numeric|min:0', // Validate that amount is numeric and non-negative
            'dueDate' => 'required|date', // Validate that dueDate is a valid date
            'classID' => 'string|regex:/^CL\d{3}$/', // Validate that classID is in the correct format (CLXXX)
            'year' => 'required|integer|in:1,2,3,4,5,6', // Validate year must be between 1-6
        ]);

        $feeData = [
            'Details' => $request->details,
            'Amount' => $request->amount,
            'DueDate' => $request->dueDate,
            'ClassID' => $request->classID,
            'Year' => $request->year,
            'global' => $request->has('global') ? 1 : 0, // Set global to true if checked, otherwise false
        ];

        $fee->update($feeData);

        return redirect()->route('manage-fees')->with('success', 'Fee updated successfully.');
    }

    public function destroy($feeID)
    {
        // Find the fee by FeeID
        $fee = Fee::where('FeeID', $feeID)->first(); // Use where instead of find

        if ($fee) {
            $fee->delete(); // Delete the fee if found
            return redirect()->route('manage-fees')->with('success', 'Fee deleted successfully.'); // Redirect with success message
        }

        // Redirect with error message if fee not found
        return redirect()->route('manage-fees')->with('error', 'Fee not found.');
    }
}
