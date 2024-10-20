<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Payment Gateway</title>
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Set a fixed viewport size resembling 4:3 aspect ratio */
        body {
            width: 800px;
            /* Width for a 4:3 ratio */
            height: 600px;
            /* Height for a 4:3 ratio */
            margin: 0 auto;
            background-color: #00aeff;
            /* Light gray background */
            color: #333;
            /* Dark gray text */
            font-family: 'Times New Roman', Times, serif;
            /* Serif font for a more formal look */
            text-align: center;
            border: 2px solid #aaa;
            /* Subtle border */
        }

        /* Headings */
        h1 {
            color: #555;
            /* Muted gray */
            font-size: 2.5em;
            /* Large heading */
        }

        /* Paragraphs */
        p {
            color: #666;
            /* Slightly darker gray */
            font-size: 1em;
            /* Normal font size */
            line-height: 1.5;
            /* Spacing for readability */
            margin: 10px 20px;
            /* Spacing for paragraphs */
        }

        /* Links */
        a {
            color: #007BFF;
            /* Standard blue for links */
            text-decoration: none;
            /* Remove underline */
        }

        a:hover {
            text-decoration: underline;
            /* Underline on hover */
        }

        /* Select elements */
        select {
            background-color: #f0f0f0;
            /* Light gray */
            color: #333;
            /* Dark gray text */
            border: 1px solid #aaa;
            /* Subtle border */
            padding: 5px;
            font-size: 1em;
            /* Font size */
        }

        /* Buttons */
        button {
            background-color: #ccc;
            /* Light gray button */
            color: #333;
            /* Dark gray text */
            border: 1px solid #aaa;
            /* Subtle border */
            padding: 10px;
            font-size: 1em;
            /* Font size */
            cursor: pointer;
            /* Pointer cursor */
        }

        /* Add some retro styling to inputs */
        input {
            background-color: #ffffff;
            /* White background */
            border: 1px solid #aaa;
            /* Subtle border */
            padding: 5px;
            color: #333;
            /* Dark gray text */
        }

        /* Add some spacing for elements */
        h1,
        p,
        select,
        button,
        input {
            margin: 20px 0;
            /* Spacing between elements */
        }

        /* 90s styling for lists */
        ul {
            list-style-type: disc;
            /* Disc bullets for lists */
            padding-left: 20px;
            /* Indentation */
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1>Payment Gateway</h1>

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('savePayment') }}" method="POST">
            @csrf

            <input type="hidden" name="identityCardNumber" value="{{ $identityCardNumber }}">
            <input type="hidden" name="selectedFees" value="{{ $selectedFees }}">
            <input type="hidden" name="totalAmount" value="{{ $totalAmount }}">

            <div class="mb-3">
                <label for="bank" class="form-label">Select Bank:</label>
                <select name="bank" id="bank" class="form-select" required>
                    <option value="">Choose a bank</option>
                    <option value="Bank A">Bank A</option>
                    <option value="Bank B">Bank B</option>
                    <option value="Bank C">Bank C</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="account" class="form-label">Select Account Type:</label>
                <select name="account" id="account" class="form-select" required>
                    <option value="">Choose an account type</option>
                    <option value="Credit">Credit Account</option>
                    <option value="Debit">Debit Account</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Make Payment</button>
        </form>
    </div>
</body>

</html>