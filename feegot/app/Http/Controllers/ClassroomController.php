<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $classrooms = Classroom::with('teacher')->get(); // Fetch all classrooms with related teachers
        $teachers = User::where('role', 1)->get(); // Fetch all teachers
        return view('classroom.index', compact('classrooms', 'teachers', 'user')); // Pass teachers to the view
    }
    public function store(Request $request)
    {
        $request->validate([
            'className' => 'required|string|max:255',
            'year' => 'required|integer|in:1,2,3,4,5,6', // Validate year must be between 1-6
            'teacherID' => 'required|exists:users,id', // Validate teacherID must exist in users table
        ]);

        // Generate unique classID
        try {
            $classID = $this->generateClassID();
        } catch (\Exception $e) {
            return redirect('/classrooms')->with('error', $e->getMessage()); // Handle max limit
        }

        $classroomData = [
            'className' => $request->className,
            'year' => $request->year,
            'classID' => $classID, // Use generated class ID
            'teacherID' => $request->teacherID, // Include teacherID in the classroom data
        ];

        Classroom::create($classroomData); // Create new classroom entry

        return redirect('/classrooms')->with('success', 'Classroom created successfully.'); // Redirect with success message
    }

    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id); // Find the classroom by ID

        $request->validate([
            'className' => 'required|string|max:255',
            'year' => 'required|integer|in:1,2,3,4,5,6', // Validate year must be between 1-6
            'teacherID' => 'required|exists:users,id', // Validate teacherID must exist in users table
        ]);

        $classroomData = $request->only('className', 'year', 'teacherID'); // Include className, year, and teacherID in the update

        $classroom->update($classroomData); // Update classroom data

        return redirect()->route('manage-classrooms')->with('success', 'Classroom updated successfully.'); // Redirect with success message
    }

    public function destroy($id)
    {
        $classroom = Classroom::find($id); // Find the classroom by ID
        if ($classroom) {
            $classroom->delete(); // Delete classroom
            return redirect()->route('manage-classrooms')->with('success', 'Classroom deleted successfully.'); // Redirect with success message
        }
        return redirect()->route('manage-classrooms')->with('error', 'Classroom not found.'); // Redirect with error message if classroom not found
    }

    private function generateClassID()
    {
        // Get the latest classroom with a classID
        $latestClassroom = Classroom::orderBy('classID', 'desc')->first();

        // Default to 1 if no classrooms exist
        $newIDNumber = 1;

        if ($latestClassroom) {
            // Extract the numeric part of the last classID
            $latestID = (int) substr($latestClassroom->classID, 2); // Remove "CL" and convert to integer

            // Check if the latest ID number is less than 999
            if ($latestID < 999) {
                $newIDNumber = $latestID + 1; // Increment by 1
            } else {
                // Handle case when reaching 999
                throw new \Exception('Maximum number of classrooms reached.');
            }
        }

        // Ensure new ID is formatted as CL000 (3 digits)
        return 'CL' . str_pad($newIDNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getClassesByYear($year)
    {
        $classes = Classroom::where('year', $year)->get();
        return response()->json($classes);
    }
}
