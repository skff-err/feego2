<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
		integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	@vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
	@include('nav')

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
	<div class="container-fluid">

		@if ($user->role==2)
		<div class="card card-body mt-3">
			<h1 class="px-3">Guardian</h1>
			<div class="row mt-2 mb-3">

				@foreach($children as $child)
				<div class="col d-flex flex-column align-items-center">
					<div id="item" class="position-relative mb-3">
						<span class="position-absolute top-50 start-50 translate-middle text-center">
							<h1><i class="fas homepage fa-user"></i></h1>
						</span>
					</div>
					<span><a id="homepageA" href="/payments/create/{{ $child->IdentityCardNumber }}">Year {{
							$child->Year }} &bull; {{ $child->Name }}</a></span>
				</div>
				@endforeach

				<div class="col d-flex flex-column align-items-center">
					<div id="item" class="position-relative mb-3">
						<span class="position-absolute top-50 start-50 translate-middle">
							<h1><i class="fas homepage fa-history"></i></h1>
						</span>
					</div>
					<span><a id="homepageA" href="{{ route('payment-history') }}">Payment History</a></span>
				</div>
			</div>
			@endif


			@if ($user->role==1)
			<div class="card card-body mt-3">
				<h1 class="px-3">Teacher</h1>
				<div class="row mt-2 mb-3">
					<div class="col d-flex flex-column align-items-center">
						<div id="item" class="position-relative mb-3">
							<span class="position-absolute top-50 start-50 translate-middle">
								<h1><i class="fas homepage fa-check"></i></h1>
							</span>
						</div>
						<span><a id="homepageA" href="{{ route('verify-payments')}}">Verify Payments</a></span>
					</div>

					<div class="col d-flex flex-column align-items-center">
						<div id="item" class="position-relative mb-3">
							<span class="position-absolute top-50 start-50 translate-middle">
								<h1><i class="fas homepage fa-users"></i></h1>
							</span>
						</div>
						<span><a id="homepageA" href="{{ route('manage-students') }}">Manage Students</a></span>
					</div>
				</div>
			</div>
			@endif

			@if ($user->role==0)
			<div class="card card-body mt-3">
				<h1 class="px-3">Management</h1>

				<div class="row mt-2 mb-3">
					<div class="col d-flex flex-column align-items-center">
						<div id="item" class="position-relative mb-3">
							<span class="position-absolute top-50 start-50 translate-middle">
								<h1><i class="fas homepage fa-file-invoice"></i></h1>
							</span>
						</div>
						<span><a id="homepageA" href="{{ route('validate-payments')}}">Validate Payments</a></span>
					</div>

					<div class="col d-flex flex-column align-items-center">
						<div id="item" class="position-relative mb-3">
							<span class="position-absolute top-50 start-50 translate-middle">
								<h1><i class="fas homepage fa-chart-line"></i></h1>
							</span>
						</div>
						<span><a id="homepageA" href="{{ route('payment-reports')}}">Generate Payments Report</a></span>
					</div>

					<div class="col d-flex flex-column align-items-center">
						<div id="item" class="position-relative mb-3">
							<span class="position-absolute top-50 start-50 translate-middle">
								<h1><i class="fas homepage fa-money-bill-alt"></i></h1>
							</span>
						</div>
						<span><a id="homepageA" href="{{ route('manage-fees') }}">Fees Management</a></span>
					</div>

					<div class="col d-flex flex-column align-items-center">
						<div id="item" class="position-relative mb-3">
							<span class="position-absolute top-50 start-50 translate-middle">
								<h1><i class="fas homepage fa-chalkboard-teacher"></i></h1>
							</span>
						</div>
						<span><a id="homepageA" href="{{ route('manage-classrooms') }}">Class Management</a></span>
					</div>

					<div class="col d-flex flex-column align-items-center">
						<div id="item" class="position-relative mb-3">
							<span class="position-absolute top-50 start-50 translate-middle">
								<h1><i class="fas homepage fa-users"></i></h1>
							</span>
						</div>
						<span><a id="homepageA" href="{{ route('manage-students') }}">Manage Students</a></span>
					</div>

					<div class="col d-flex flex-column align-items-center">
						<div id="item" class="position-relative mb-3">
							<span class="position-absolute top-50 start-50 translate-middle">
								<h1><i class="fas homepage fa-user-cog"></i></h1>
							</span>
						</div>
						<span><a id="homepageA" href="{{ route('manage-users') }}">Users Management</a></span>
					</div>

				</div>
			</div>
			@endif

	</div>
</body>

</html>