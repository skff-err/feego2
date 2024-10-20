<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Classroom Management</title>
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
        <h1>Classroom Management</h1>

        <h2>Add New Classroom</h2>
        <form action="/classrooms" method="POST" id="classroomForm">
            @csrf
            <div class="row justify-content-center align-items-center g-2">
                <div class="col">
                    <div class="mb-3">
                        <label for="className" class="form-label">Class Name:</label>
                        <input type="text" name="className" class="form-control" required>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="year" class="form-label">Year:</label>
                        <select name="year" id="year" class="form-select" required>
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
            <div class="mb-3">
                <label for="teacherID" class="form-label">Assign Teacher:</label>
                <select name="teacherID" id="teacherID" class="form-select" required>
                    <option value="">Assign teacher</option>
                    @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Classroom</button>
        </form>

        <h2 class="mt-4">Existing Classrooms</h2>
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Year</th>
                    <th>Teacher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classrooms as $classroom)
                <tr>
                    <td>{{ $classroom->classID }}</td>
                    <td>{{ $classroom->className }}</td>
                    <td>{{ $classroom->year }}</td>
                    <td>{{ $classroom->teacher->name ?? 'N/A' }}</td> <!-- Display teacher name if exists -->
                    <td>
                        <button type="button" class="btn btn-warning btn-sm"
                            onclick="openEditModal({{ $classroom }})">Edit</button>
                        <form action="/classrooms/{{ $classroom->classID }}" method="POST" style="display:inline;"
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

    <!-- Edit Classroom Modal -->
    <div class="modal fade" id="editClassroomModal" tabindex="-1" aria-labelledby="editClassroomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editClassroomModalLabel">Edit Classroom</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <form id="editClassroomForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="classID" id="editClassroomId">

                        <div class="row justify-content-center align-items-center g-2">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="editClassName" class="form-label">Class Name:</label>
                                    <input type="text" id="editClassName" name="className" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="editYear" class="form-label">Year:</label>
                                    <select name="year" id="editYear" class="form-select" required>
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

                        <div class="mb-3">
                            <label for="editTeacherID" class="form-label">Assign Teacher:</label>
                            <select name="teacherID" id="editTeacherID" class="form-select" required>
                                <option value="">Assign teacher</option>
                                @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                onclick="closeModal()">Close</button>
                            <button type="submit" class="btn btn-primary">Update Classroom</button>
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

    function openEditModal(classroom) {
        // Populate fields
        document.getElementById('editClassroomId').value = classroom.classID;
        document.getElementById('editClassName').value = classroom.className;
        document.getElementById('editYear').value = classroom.year;
        document.getElementById('editTeacherID').value = classroom.teacherID;

        const form = document.getElementById('editClassroomForm');
        form.action = `/classrooms/update/${classroom.classID}`;

        const modal = document.getElementById('editClassroomModal');
        modal.classList.add('show');
        modal.style.display = 'block';
    }

    function closeModal() {
        const modal = document.getElementById('editClassroomModal');
        modal.classList.remove('show');
        modal.style.display = 'none'; // Hide modal
    }

    document.addEventListener('click', function(event) {
            const modal = document.getElementById('editClassroomModal');
            if (event.target === modal) {
            closeModal();
        }
    })
    </script>


</body>

</html>