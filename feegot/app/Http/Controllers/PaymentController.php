<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Fee;
use App\Models\Student;
use App\Models\Report;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index($identityCardNumber)
    {
        $student = Student::where('IdentityCardNumber', $identityCardNumber)->first();
        $user = Auth::user();

        $classID = $student->ClassID;
        $year = $student->Year; // Assuming you have a Year field in your Student model

        // Fetch the class-specific fees
        $classFees = Fee::where('ClassID', $classID)->get();

        // Fetch the global fees for the student's year
        $globalFees = Fee::where('global', 1)->where('Year', $year) // Make sure there's a Year column in the Fee model
            ->get();

        // Get the fees already paid by the guardian
        $paidFees = Payment::where('IdentityCardNumber', $identityCardNumber)
            ->pluck('FeeID')->toArray();

        // Exclude already paid fees from the class-specific and global fees
        $availableFees = $classFees->whereNotIn('FeeID', $paidFees)->merge($globalFees->whereNotIn('FeeID', $paidFees)); // Combine class fees and global fees

        return view('payment.index', compact('availableFees', 'student', 'user'));
    }

    public function paymentHistory(Request $request)
{
    // Fetch the currently authenticated guardian (user)
    $guardianID = Auth::id(); // Assuming Auth user is the guardian
    $user = Auth::user();

    // Retrieve all students associated with this guardian (by their GuardianID)
    $students = Student::where('GuardianID', $guardianID)->get();

    // Retrieve all IdentityCardNumbers from those students
    $studentIDs = $students->pluck('IdentityCardNumber');

    // Retrieve all payments made by those students (using the students' IdentityCardNumber)
    $payments = Payment::whereIn('IdentityCardNumber', $studentIDs)->get();

    // Pass both payments and student info to the view
    return view('payment.history', compact('payments', 'students', 'user'));
}

    // Display the mock payment gateway
    public function mockPayment(Request $request)
    {
        $selectedFees = $request->input('selectedFees'); // Get selected fees from the previous form
        $identityCardNumber = $request->input('identityCardNumber'); // Get identity card number
        $totalAmount = $request->input('totalAmount'); // Get total amount from the previous form

        return view('payment.gateway', compact('identityCardNumber', 'selectedFees', 'totalAmount'));
    }

    // Store payment details
    public function store(Request $request)
    {
        $request->validate([
            'bank' => 'required',
            'account' => 'required',
            'identityCardNumber' => 'required|string',
            'selectedFees' => 'required|string',
        ]);

        $identityCardNumber = $request->identityCardNumber;
        $selectedFees = explode(',', $request->selectedFees); // Convert to array

        foreach ($selectedFees as $feeID) {
            $fee = Fee::findOrFail($feeID);

            // Create payment record
            Payment::create([
                'IdentityCardNumber' => $identityCardNumber,
                'FeeID' => $feeID,
                'Method' => 'FPX', // Online Banking
                'Amount' => $fee->Amount,
                'TeacherAppr' => 'pending',
                'AdminAppr' => 'pending',
                'Status' => 'pending',
            ]);
        }

        // Redirect back to the payment index route with a success message
        return redirect()->route('make-payment', ['identityCardNumber' => $identityCardNumber])
            ->with('success', 'Payment processed successfully.');
    }

    public function verifyPayments(Request $request)
    {
        // Assuming the logged-in user is a teacher and we need to get their ID
        $teacherID = $request->user()->id; // Adjust based on your auth system
        $user = Auth::user();

        // Get the students who are in the classes taught by this teacher
        $students = Student::whereHas('classroom', function ($query) use ($teacherID) {
            $query->where('TeacherID', $teacherID); // Ensure the classroom has this teacher assigned
        })->pluck('IdentityCardNumber')->toArray();

        // Get pending payments for those students
        $payments = Payment::whereIn('IdentityCardNumber', $students)
            ->where('Status', 'pending') // Check for pending payments
            ->with('fee') // Load associated fee details
            ->get();

        // Pass the $payments variable to the view
        return view('payment.verify', compact('payments', 'user'));
    }

    public function approve($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->TeacherAppr = 'approved'; // or any other logic
        $payment->save();

        return redirect()->route('verify-payments')->with('success', 'Payment approved successfully.');
    }

    public function reject($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->TeacherAppr = 'denied'; // or any other logic
        $payment->save();

        return redirect()->route('verify-payments')->with('success', 'Payment rejected successfully.');
    }

    public function validatePayments(Request $request)
    {
        // Get pending payments
        $payments = Payment::where('Status', 'pending') // Check for pending payments
            ->with('fee') // Load associated fee details
            ->get();

        $user = Auth::user();


        // Pass the $payments variable to the view
        return view('payment.validate', compact('payments', 'user'));
    }

    public function validate($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->AdminAppr = 'approved'; // Set Admin approval status
        $payment->Status = 'verified'; // Update the overall payment status
        $payment->save();

        return redirect()->route('validate-payments')->with('success', 'Payment approved successfully.');
    }

    public function deny($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->AdminAppr = 'denied'; // Set Teacher denial status
        $payment->Status = 'denied'; // Update the overall payment status
        $payment->save();

        return redirect()->route('validate-payments')->with('success', 'Payment denied successfully.');
    }

    public function report(Request $request)
    {
        $user = Auth::user();
        // Fetch distinct years from the Fee table
        $years = Fee::distinct()->pluck('Year');

        // Initialize variables
        $totalPaid = 0;
        $totalUnpaid = 0;
        $percentagePaid = 0;
        $totalFees = 0;
        $totalStudents = 0;
        $studentsPaidCount = 0;
        $reports = Report::all(); // Fetch all generated reports

        if ($request->isMethod('post')) {
            // Validate input
            $request->validate([
                'year' => 'required|integer',
                'month' => 'required|integer|min:1|max:12',
            ]);

            // Fetch all class IDs for the selected year
            $classIDs = Classroom::where('Year', $request->year)->pluck('ClassID');

            // Count all students enrolled in those classes
            $totalStudents = Student::whereIn('ClassID', $classIDs)->count();

            // Get total paid amounts
            $totalPaid = Payment::whereMonth('updated_at', $request->month)
                ->where('Status', 'verified')
                ->sum('Amount');

            // Count the number of unique students who have paid
            $studentsPaidCount = Payment::whereMonth('updated_at', $request->month)
                ->where('Status', 'verified')
                ->distinct('IdentityCardNumber')
                ->count('IdentityCardNumber');

            // Calculate total unpaid
            
            // Get total fees based on the number of students
            $totalFees = Fee::where('Year', $request->year)->sum('Amount') * $totalStudents;
            $totalUnpaid = $totalFees - $totalPaid;

            // Calculate the percentage paid
            $percentagePaid = $totalFees > 0 ? ($totalPaid / $totalFees) * 100 : 0;
        }

        return view('payment.report', compact('user','years', 'totalPaid', 'totalUnpaid', 'percentagePaid', 'totalFees', 'totalStudents', 'studentsPaidCount', 'reports'));
    }
    public function save(Request $request)
    {
        // Validate input
        $request->validate([
            'totalPaid' => 'required|numeric',
            'totalUnpaid' => 'required|numeric',
            'year' => 'required|integer',
            'month' => 'required|string',
            'percentPaid' => 'required|numeric', // Add validation for percentPaid
            'totalAmount' => 'required|numeric', // Add validation for totalAmount
        ]);

        // Create a new report record
        Report::create([
            'generatedBy' => Auth::id(), // Assuming user is authenticated
            'totalPaid' => $request->totalPaid,
            'totalUnpaid' => $request->totalUnpaid,
            'percentPaid' => $request->percentPaid, // Save percentPaid
            'totalAmount' => $request->totalAmount, // Save totalAmount
            'Year' => $request->year,
            'forMonth' => $request->month,
        ]);

        return redirect()->route('payment-reports')->with('success', 'Report saved successfully.');
    }
}
