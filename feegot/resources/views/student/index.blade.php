<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Student Registration</title>
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

		@if (session('error'))
		<div class="alert alert-danger">
			{{ session('error') }}
		</div>
		@endif

		<h1>Student Registration</h1>

		<h2>Add New Student</h2>
		<form action="/students" method="POST" id="studentForm">
			@csrf

			<div class="row justify-content-center align-items-center g-2">
				<div class="col">
					<div class="mb-3">
						<label for="identityCardNumber" class="form-label">Identity Card Number:</label>
						<input type="text" name="identityCardNumber" class="form-control" required>
					</div>
				</div>
				<div class="col">
					<div class="mb-3">
						<label for="name" class="form-label">Name:</label>
						<input type="text" name="name" class="form-control" required>
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

			<div class="mb-3">
				<label for="guardianID" class="form-label">Guardian:</label>
				<select name="guardianID" id="guardianID" class="form-select" required>
					<option value="">Select Guardian</option>
					@foreach ($guardians as $guardian)
					<option value="{{ $guardian->id }}">{{ $guardian->id }} &bull; {{ $guardian->name }}</option>
					@endforeach
				</select>
			</div>

			<button type="submit" class="btn btn-primary">Register Student</button>
		</form>

		<h2 class="mt-4">Registered Students</h2>
		<table class="table table-bordered mt-2">
			<thead>
				<tr>
					<th>Identity Card Number</th>
					<th>Name</th>
					<th>Class</th>
					<th>Year</th>
					<th>Guardian</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($students as $student)
				<tr>
					<td>{{ $student->IdentityCardNumber }}</td>
					<td>{{ $student->Name }}</td>
					<td>{{ $student->ClassID }}</td>
					<td>{{ $student->Year }}</td>
					<td>{{ $student->GuardianID }}</td>
					<td>
						<button type="button" class="btn btn-warning btn-sm"
							onclick="openEditModal({{ json_encode($student) }})">Edit</button>
						<form action="/students/{{ $student->IdentityCardNumber }}" method="POST"
							style="display:inline;" onsubmit="return confirmDelete();">
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

	<!-- Edit Student Modal -->
	<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="editStudentModalLabel">Edit Student</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
						onclick="closeModal()"></button>
				</div>
				<div class="modal-body">
					<form id="editStudentForm" method="POST" action="">
						@csrf
						<input type="hidden" name="identityCardNumber" id="editIdentityCardNumber">

						<div class="row justify-content-center align-items-center g-2">
							<div class="col">
								<div class="mb-3">
									<label for="editName" class="form-label">Name:</label>
									<input type="text" id="editName" name="name" class="form-control" required>
								</div>
							</div>
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
						</div>

						<div class="row justify-content-center align-items-center g-2">
							<div class="col">
								<div class="mb-3">
									<label for="editClassID" class="form-label">Class ID:</label>
									<select name="classID" id="editClassID" class="form-select" required disabled>
										<option value="">Select Class</option>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="mb-3">
									<label for="editGuardianID" class="form-label">Guardian ID:</label>
									<select name="guardianID" id="editGuardianID" class="form-select" required>
										<option value="">Select Guardian</option>
										@foreach ($guardians as $guardian)
										<option value="{{ $guardian->id }}">{{ $guardian->id }}. {{ $guardian->name }}
										</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
								onclick="closeModal()">Close</button>
							<button type="submit" class="btn btn-primary">Update Student</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>

<script>
	function confirmDelete() {
		return confirm('Are you sure you want to delete this student? This action cannot be undone.');
	}

	function openEditModal(student) {
		// Populate fields
		document.getElementById('editIdentityCardNumber').value = student.IdentityCardNumber;
		document.getElementById('editName').value = student.Name;

		// Set the action for the form
		const form = document.getElementById('editStudentForm');
		form.action = `/students/update/${student.IdentityCardNumber}`; // Set the correct action URL for updating

		// Load class options based on selected year
		loadClasses(student.Year, student.ClassID); // Pre-select classID

		// Show modal
		const modal = document.getElementById('editStudentModal');
		modal.classList.add('show');
		modal.style.display = 'block'; // Ensure it's displayed
		}

		function closeModal() {
		const modal = document.getElementById('editStudentModal');
		modal.classList.remove('show');
		modal.style.display = 'none'; // Hide modal
		}

		document.addEventListener('click', function(event) {
		const modal = document.getElementById('editStudentModal');
		if (event.target === modal) {
			closeModal();
		}
	});

	

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

	document.getElementById('year').addEventListener('change', function () {
		const selectedYear = this.value;
		const classIDSelect = document.getElementById('classID');

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
						option.value = classItem.classID;
						option.textContent = classItem.classID + ' \u2022 ' + classItem.className;
						classIDSelect.appendChild(option);
					});
				})
				.catch(error => {
					console.error('Error fetching classes:', error);
				});
		}
	});
</script>

</html>