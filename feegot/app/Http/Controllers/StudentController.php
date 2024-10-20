<?php

namespace App\Http\Controllers;

use App\Models\Student; // Import the Student model
use App\Models\User;    // Import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $students = Student::all(); // Fetch all students
        $guardians = User::where('role', 2)->get(); // Fetch guardians from users table (assuming you have a 'role' column)

        return view('student.index', compact('students', 'guardians', 'user')); // Pass students and guardians to the view
    }

    public function store(Request $request)
    {
        // Validation rules
        $request->validate([
            'identityCardNumber' => 'required|string|max:20|unique:students,IdentityCardNumber', // Validate unique identity card number
            'name' => 'required|string|max:255', // Validate name is required and max length
            'year' => 'required|integer|in:1,2,3,4,5,6', // Validate year must be between 1-6
            'classID' => 'required|string', // Validate classID format
            'guardianID' => 'required|exists:users,id', // Validate guardian ID exists
        ]);

        // Prepare student data
        $studentData = [
            'IdentityCardNumber' => $request->identityCardNumber,
            'Name' => $request->name,
            'Year' => $request->year,
            'ClassID' => $request->classID,
            'GuardianID' => $request->guardianID,
        ];

        // Create new student entry
        Student::create($studentData);

        // Redirect with success message
        return redirect('/students')->with('success', 'Student registered successfully.');
    }

    public function update(Request $request, $id)
    {
        try {
            $student = Student::where('IdentityCardNumber', $id)->firstOrFail(); // Fetch student by ID

            // Validation rules
            $request->validate([
                'name' => 'required|string|max:255', // Validate name
                'year' => 'required|integer|in:1,2,3,4,5,6', // Validate year
                'classID' => 'required|string', // Validate classID format
                'guardianID' => 'required|exists:users,id', // Validate guardian ID exists
            ]);

            // Prepare student data
            $studentData = [
                'Name' => $request->name,
                'Year' => $request->year,
                'ClassID' => $request->classID,
                'GuardianID' => $request->guardianID,
            ];

            // Update student information
            $student->update($studentData);

            // Redirect with success message
            return redirect()->route('manage-students')->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            // If something goes wrong (e.g., validation fails or the student is not found)
            return redirect()->back()->with('error',$e->getMessage())->withInput();
        }
    }


    public function destroy($IdentityCardNumber)
    {
        // Find the student by Identity Card Number
        $student = Student::where('IdentityCardNumber', $IdentityCardNumber)->first();

        if ($student) {
            $student->delete(); // Delete the student if found
            return redirect()->route('manage-students')->with('success', 'Student deleted successfully.');
        }

        // Redirect with error message if student not found
        return redirect()->route('manage-students')->with('error', 'Student not found.');
    }
}
