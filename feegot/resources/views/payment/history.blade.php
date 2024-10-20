<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    @include('nav')
    <div class="container mt-4">

        <h1>Payment History</h1>

        @if ($payments->isEmpty())
        <div class="alert alert-info">No payment history found.</div>
        @else
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Student Name</th> <!-- Add student name column -->
                    <th>Identity Card Number</th> <!-- Add IdentityCardNumber column -->
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Paid</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                @php
                // Find the student associated with this payment's IdentityCardNumber
                $student = $students->firstWhere('IdentityCardNumber', $payment->IdentityCardNumber);
                @endphp
                <tr>
                    <td>{{ $payment->PaymentID }}</td>
                    <td>{{ $student ? $student->Name : 'N/A' }}</td> <!-- Display student name -->
                    <td>{{ $payment->IdentityCardNumber }}</td> <!-- Display student IdentityCardNumber -->
                    <td>{{ number_format($payment->Amount, 2) }}</td>
                    <td>{{ $payment->Status }}</td>
                    <td>{{ $payment->updated_at->format('Y-m-d H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>


</body>

</html>