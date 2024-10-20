<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Payments</title>
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
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h1>Payments for Your Class</h1>

        @if ($payments->isEmpty())
        <div class="alert alert-info">No payments have been made by guardians for your class.</div>
        @else
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Fee ID</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Teacher Approval</th>
                    <th>Admin Approval</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->IdentityCardNumber }}</td>
                    <td>{{ $payment->fee->FeeID }}</td>
                    <td>{{ number_format($payment->Amount, 2) }}</td>
                    <td>{{ $payment->Method }}</td>
                    <td>{{ ucfirst($payment->TeacherAppr) }}</td>
                    <td>{{ ucfirst($payment->AdminAppr) }}</td>
                    <td>
                        <form action="{{ route('payments.approve', $payment->PaymentID) }}" method="POST"
                            style="display:inline;" onsubmit="return confirmDelete();">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" @if($payment->TeacherAppr ===
                                'approved' || $payment->TeacherAppr === 'denied') disabled @endif
                                onclick="return confirm('Are you sure you want to approve this payment?')">
                                Approve
                            </button>
                        </form>
                        <form action="{{ route('payments.reject', $payment->PaymentID) }}" method="POST"
                            style="display:inline;" onsubmit="return confirmDelete();">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" @if($payment->TeacherAppr === 'approved'
                                || $payment->TeacherAppr === 'denied') disabled @endif
                                onclick="return confirm('Are you sure you want to reject this payment?')">
                                Reject
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>


</body>

</html>