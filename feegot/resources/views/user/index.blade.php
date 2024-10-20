<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Management</title>
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
        <h1>User Management</h1>

        <h2>Add New User</h2>
        <form action="/users" method="POST" id="userForm">
            @csrf

            <div class="row justify-content-center align-items-center g-2">
                <div class="col">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center align-items-center g-2">
                <div class="col">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password:</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="role" class="form-label">Role:</label>
                        <select name="role" id="role" class="form-select" required onchange="toggleGuardianFields()">
                            <option value="0">Admin</option>
                            <option value="1">Teacher</option>
                            <option value="2">Guardian</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Guardian specific fields -->
            <div id="guardianFields" style="display: none;">
                <div class="row justify-content-center align-items-center g-2">
                    <div class="col">
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number:</label>
                            <input type="text" name="phone_number" class="form-control">
                        </div>
                    </div>
                    <div class="col">

                        <div class="mb-3">
                            <label for="address" class="form-label">Address:</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create User</button>
        </form>

        <h2 class="mt-4">Existing Users</h2>
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @switch($user->role)
                        @case(0)
                        Admin
                        @break
                        @case(1)
                        Teacher
                        @break
                        @case(2)
                        Guardian
                        @break
                        @default
                        Unknown Role
                        @endswitch
                    </td>
                    <td>{{ $user->role == 2 ? $user->phone_number : 'N/A' }}</td>
                    <td>{{ $user->role == 2 ? $user->address : 'N/A' }}</td>
                    <td>
                        @if (Auth::user()->id !== $user->id)
                        <button type="button" class="btn btn-warning btn-sm"
                            onclick="openEditModal({{ $user }})">Edit</button>
                        <form action="/users/{{ $user->id }}" method="POST" style="display:inline;"
                            onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        @else
                        <span class="text-muted">Cannot delete own account</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editUserModalLabel">Edit User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">

                    <form id="editUserForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="POST"> <!-- Keep this hidden for Laravel -->
                        <input type="hidden" name="user_id" id="editUserId">

                        <div class="row justify-content-center align-items-center g-2">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="editName" class="form-label">Name:</label>
                                    <input type="text" id="editName" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email:</label>
                                    <input type="email" id="editEmail" name="email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role:</label>
                            <select name="role" id="editRole" class="form-select" required
                                onchange="toggleGuardianFieldsEdit()">
                                <option value="0">Admin</option>
                                <option value="1">Teacher</option>
                                <option value="2">Guardian</option>
                            </select>
                        </div>

                        <!-- Guardian specific fields -->
                        <div id="editGuardianFields" style="display: none;">
                            <div class="row justify-content-center align-items-center g-2">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="editPhoneNumber" class="form-label">Phone Number:</label>
                                        <input type="text" id="editPhoneNumber" name="phone_number"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="editAddress" class="form-label">Address:</label>
                                        <input type="text" id="editAddress" name="address" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Close</button>
							<button type="submit" class="btn btn-primary">Update User</button>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function confirmDelete() {
        return confirm('Are you sure you want to delete this student? This action cannot be undone.');
    }

        function toggleGuardianFields() {
            const roleSelect = document.getElementById('role');
            const guardianFields = document.getElementById('guardianFields');
            guardianFields.style.display = roleSelect.value === '2' ? 'block' : 'none';
        }

        function toggleGuardianFieldsEdit() {
            const roleSelect = document.getElementById('editRole');
            const editGuardianFields = document.getElementById('editGuardianFields');
            editGuardianFields.style.display = roleSelect.value === '2' ? 'block' : 'none';
        }

        function openEditModal(user) {
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editName').value = user.name;
    document.getElementById('editEmail').value = user.email;
    const roleSelect = document.getElementById('editRole');
    roleSelect.value = user.role;
    toggleGuardianFieldsEdit();

    if (user.role == 2) {
        document.getElementById('editPhoneNumber').value = user.phone_number || '';
        document.getElementById('editAddress').value = user.address || '';
    } else {
        document.getElementById('editPhoneNumber').value = '';
        document.getElementById('editAddress').value = '';
    }

    const form = document.getElementById('editUserForm');
    form.action = `/users/update/${user.id}`; // Update action URL to POST

    const modal = document.getElementById('editUserModal');
    modal.classList.add('show');
    modal.style.display = 'block'; // Ensure it's displayed

}

function closeModal() {
    const modal = document.getElementById('editUserModal');
    modal.classList.remove('show');
    modal.style.display = 'none'; // Hide modal
}

document.addEventListener('click', function(event) {
    const modal = document.getElementById('editUserModal');
    if (event.target === modal) {
        closeModal();
    }
})

    </script>
    


</body>

</html>