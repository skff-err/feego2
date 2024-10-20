<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Payment for {{ $student->Name }}</title>
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
        <h1>Create Payment for {{ $student->Name }}</h1>

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

        <form action="{{ route('payment-gateway') }}" method="POST">
            @csrf
            <input type="hidden" name="identityCardNumber" value="{{ $student->IdentityCardNumber }}">
            <input type="hidden" name="selectedFees" id="selectedFees" value=""> <!-- This will hold selected FeeIDs -->

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"> Select
                        </th>
                        <th>Fee ID</th>
                        <th>Details</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($availableFees as $fee)
                    <tr>
                        <td>
                            <input type="checkbox" class="fee-checkbox" data-amount="{{ $fee->Amount }}"
                                data-fee-id="{{ $fee->FeeID }}" onclick="calculateTotal()">
                        </td>
                        <td>{{ $fee->FeeID }}</td>
                        <td>{{ $fee->Details }}</td>
                        <td>{{ number_format($fee->Amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mb-3">
                <label for="totalAmount" class="form-label">Total Amount:</label>
                <span class="form-control-plaintext">
                    <p id="totalAmount" class="fs-2 fw-bold">RM 0.00</p>
                </span>

                <button type="submit" class="btn btn-primary" id="payButton" disabled
                    onclick="prepareSelectedFees()">Pay with FPX</button>
            </div>
        </form>
    </div>


    <script>
        function confirmDelete() {
        return confirm('Are you sure you want to delete this student? This action cannot be undone.');
    }

        function calculateTotal() {
            const checkboxes = document.querySelectorAll('.fee-checkbox');
            let total = 0;
            let anyChecked = false;

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.dataset.amount);
                    anyChecked = true; // Set to true if any checkbox is checked
                }
            });

            // Update the total amount display
            document.getElementById('totalAmount').textContent = 'RM ' + total.toFixed(2);

            // Enable or disable the pay button based on checkbox selection
            document.getElementById('payButton').disabled = !anyChecked;
        }

        function toggleSelectAll(selectAllCheckbox) {
            const checkboxes = document.querySelectorAll('.fee-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            calculateTotal(); // Recalculate total amount whenever select all is toggled
        }

        function prepareSelectedFees() {
            const checkboxes = document.querySelectorAll('.fee-checkbox');
            const selectedFees = [];

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    selectedFees.push(checkbox.dataset.feeId);
                }
            });

            document.getElementById('selectedFees').value = selectedFees.join(','); // Store selected FeeIDs
        }
    </script>


</body>

</html>