<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>Fee Management</title>
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
		<h1>Fee Management</h1>

		<h2>Add New Fee</h2>
		<form action="/fees" method="POST" id="feeForm">
			@csrf

			<div class="mb-3">
				<label for="details" class="form-label">Title:</label>
				<input type="text" name="details" class="form-control" required>
			</div>

			<div class="row justify-content-center align-items-center g-2">
				<div class="col">
					<div class="mb-3">
						<label for="amount" class="form-label">Amount:</label>
						<input type="number" name="amount" class="form-control" required min="0">
					</div>
				</div>
				<div class="col">
					<div class="mb-3">
						<label for="dueDate" class="form-label">Due Date:</label>
						<input type="date" name="dueDate" class="form-control" required>
					</div>
				</div>
			</div>

			<div class="row justify-content-center align-items-center g-2">
				<div class="col">
					<div class="mb-3">
						<label for="year" class="form-label">Year:</label>
						<select name="year" id="year" class="form-select" required>
							<option value="">Select Year</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
						</select>
					</div>
				</div>
				<div class="col">
					<div class="mb-3">
						<label for="classID" class="form-label">Class ID:</label>
						<select name="classID" id="classID" class="form-select" required disabled>
							<option value="">Select Class</option>
						</select>
					</div>
				</div>
			</div>

			<div class="mb-3 form-check">
				<input type="checkbox" name="global" class="form-check-input" id="globalCheckbox">
				<label class="form-check-label" for="globalCheckbox">Global Fee (Applies to all
					classes in selected year)</label>
			</div>


			<button type="submit" class="btn btn-primary">Create Fee</button>
		</form>

		<h2 class="mt-4">Existing Fees</h2>
		<table class="table table-bordered mt-2">
			<thead>
				<tr>
					<th>Fee ID</th>
					<th>Title</th>
					<th>Amount</th>
					<th>Due Date</th>
					<th>Class ID</th>
					<th>Year</th>
					<th>Global</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($fees as $fee)
				<tr>
					<td>{{ $fee->FeeID }}</td>
					<td>{{ $fee->Details }}</td>
					<td>{{ number_format($fee->Amount, 2) }}</td>
					<td>{{ $fee->DueDate }}</td>
					<td>{{ $fee->ClassID }}</td>
					<td>{{ $fee->Year }}</td>
					<td>{{ $fee->global ? 'Yes' : 'No' }}</td>
					<td>
						<button type="button" class="btn btn-warning btn-sm"
							onclick="openEditModal({{ json_encode($fee) }})">Edit</button>
						<form action="/fees/{{ $fee->FeeID }}" method="POST" style="display:inline;"
							onsubmit="return confirmDelete();">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn btn-danger btn-sm">Delete</button>
						</form>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<!-- Edit Fee Modal -->
	<div class="modal fade" id="editFeeModal" tabindex="-1" aria-labelledby="editFeeModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="editFeeModalLabel">Edit Fee</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
				</div>
				<div class="modal-body">
					<form id="editFeeForm" method="POST" action="">
						@csrf

						<div class="row justify-content-center align-items-center g-2">
							<div class="col">
								<div class="mb-3">
									<label for="feeID" class="form-label">ID:</label>
									<input class="form-control" type="text" name="feeId" id="editFeeId" disabled>
								</div>
							</div>
							<div class="col">
								<div class="mb-3">
									<label for="editDetails" class="form-label">Title:</label>
									<input class="form-control" type="text" name="details" id="editDetails">
								</div>
							</div>
						</div>

						<div class="row justify-content-center align-items-center g-2">
							<div class="col">
								<div class="mb-3">
									<label for="editAmount" class="form-label">Amount:</label>
									<input type="number" id="editAmount" name="amount" class="form-control" required
										step="1">
								</div>
							</div>
							<div class="col">
								<div class="mb-3">
									<label for="editDueDate" class="form-label">Due Date:</label>
									<input type="date" id="editDueDate" name="dueDate" class="form-control" required>
								</div>
							</div>
						</div>

						<div class="row justify-content-center align-items-center g-2">
							<div class="col">
								<div class="mb-3">
									<label for="editYear" class="form-label">Year:</label>
									<select name="year" id="editYear" class="form-select" required>
										<option value="">Select Year</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="mb-3">
									<label for="editClassID" class="form-label">Class ID:</label>
									<select name="classID" id="editClassID" class="form-select" required disabled>
										<option value="">Select Class</option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-check mb-3">
							<input type="checkbox" class="form-check-input" name="global" id="editGlobalCheckbox">
							<label class="form-check-label" for="editGlobalCheckbox">Global Fee (Applies to all
								classes in selected year)</label>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Close</button>
							<button type="submit" class="btn btn-primary">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</body>

</html>


<script>
	function confirmDelete() {
        return confirm('Are you sure you want to delete this student? This action cannot be undone.');
    }

	// Add event listener for the 'global' checkbox in the Add Fee form
    document.getElementById('globalCheckbox').addEventListener('change', function() {
        toggleClassIDField(this.checked, 'classID');
    });

    // Add event listener for the 'global' checkbox in the Edit Fee modal
    document.getElementById('editGlobalCheckbox').addEventListener('change', function() {
        toggleClassIDField(this.checked, 'editClassID');
    });

    // Function to toggle 'classID' field's required status and disable/enable it
    function toggleClassIDField(isGlobal, classIDFieldId) {
        const classIDField = document.getElementById(classIDFieldId);

        if (isGlobal) {
            classIDField.disabled = true; // Disable the field
            classIDField.removeAttribute('required'); // Remove the required attribute
        } else {
            classIDField.disabled = false; // Enable the field
            classIDField.setAttribute('required', 'required'); // Add the required attribute back
        }
    }

	function openEditModal(fee) {
        document.getElementById('editFeeId').value = fee.FeeID;
        document.getElementById('editDetails').value = fee.Details;
        document.getElementById('editAmount').value = fee.Amount;
        document.getElementById('editDueDate').value = fee.DueDate; // Use DueDate for consistency
		document.getElementById('editGlobalCheckbox').checked = fee.global;

        // Set the action for the form
        const form = document.getElementById('editFeeForm');
        form.action = `/fees/update/${fee.FeeID}`; // Set the correct action URL for updating

        // Load class options based on selected year
        loadClasses(fee.Year, fee.ClassID); // Pre-select classID

		const modal = document.getElementById('editFeeModal');
		modal.classList.add('show');
		modal.style.display = 'block'; // Ensure it's displayed
    }

	function closeModal() {
    const modal = document.getElementById('editFeeModal');
    modal.classList.remove('show');
    modal.style.display = 'none'; // Hide modal
}

document.addEventListener('click', function(event) {
    const modal = document.getElementById('editFeeModal');
    if (event.target === modal) {
        closeModal();
    }
})

	function loadClasses(year, selectedClassID) {

		document.getElementById('editYear').addEventListener('change', function() {
			const selectedYear = this.value;
			const classIDSelect = document.getElementById('editClassID');

        // Clear previous options
        classIDSelect.innerHTML = '<option value="">Select Class</option>';
        classIDSelect.disabled = true; // Disable until year is selected
        
        // If a year is selected, fetch the available classes
        if (selectedYear) {
            classIDSelect.disabled = false;
			
            // Fetch classes for the selected year
            fetch(`/classes/${selectedYear}`)
			.then(response => response.json())
                .then(data => {
                    data.forEach(classItem => {
						const option = document.createElement('option');
                        option.value = classItem.classID; // Ensure this matches your classID field
                        option.textContent = classItem.classID + ' \u2022 ' + classItem.className; // Ensure this matches your className field
                        classIDSelect.appendChild(option);
                    });
                })
                .catch(error => {
					console.error('Error fetching classes:', error);
                });
			}
		});
	}

        document.getElementById('year').addEventListener('change', function() {
        const selectedYear = this.value;
        const classIDSelect = document.getElementById('classID');

        // Clear previous options
        classIDSelect.innerHTML = '<option value="">Select Class</option>';
        
        // If a year is selected, fetch the available classes
        if (selectedYear) {
            classIDSelect.disabled = false;

            // Fetch classes for the selected year
            fetch(`/classes/${selectedYear}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(classItem => {
                        const option = document.createElement('option');
                        option.value = classItem.classID;
                        option.textContent = classItem.classID + ' \u2022 ' + classItem.className;
                        classIDSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching classes:', error);
                });
        } else {
            classIDSelect.disabled = true; // Disable classID select if no year is selected
        }
    });
</script>


</body>

</html>