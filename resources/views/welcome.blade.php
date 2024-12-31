<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Task Manager</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800">
    <div class="flex flex-col items-center justify-center">
        <header class="w-full bg-white shadow py-4 px-6">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="text-lg font-bold">Task Manager</div>
                @if (Route::has('login'))
                <nav>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-blue-500 hover:underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-blue-500 hover:underline">Register</a>
                        @endif
                    @endauth
                </nav>
                @endif
            </div>
        </header>

        <main class="w-full max-w-7xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Task List</h1>
                <p style="color: red">Login for advanced search</p>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create Task</a>
            </div>

            @if ($tasks->isEmpty())
                <p class="text-gray-600">No tasks available. Create a new one!</p>
            @else
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300 bg-white shadow-md">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2 text-left">Title</th>
                                <th class="border px-4 py-2 text-left">Status</th>
                                <th class="border px-4 py-2 text-left">Due Date</th>
                                <th class="border px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="border px-4 py-2">{{ $task->title }}</td>
                                    <td class="border px-4 py-2">{{ $task->status }}</td>
                                    <td class="border px-4 py-2">{{ $task->due_date ?? 'No Due Date' }}</td>
                                    <td class="border px-4 py-2">
                                        <button class="btn btn-info btn-sm" onclick="viewTask({{ $task }})">View</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Modal -->
            <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Title:</strong> <span id="modalTaskTitle"></span></p>
                            <p><strong>Description:</strong> <span id="modalTaskDescription"></span></p>
                            <p><strong>Status:</strong> <span id="modalTaskStatus"></span></p>
                            <p><strong>Due Date:</strong> <span id="modalTaskDueDate"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function viewTask(task) {
            document.getElementById('modalTaskTitle').textContent = task.title;
            document.getElementById('modalTaskDescription').textContent = task.description || 'No Description';
            document.getElementById('modalTaskStatus').textContent = task.status;
            document.getElementById('modalTaskDueDate').textContent = task.due_date || 'No Due Date';

            var modal = new bootstrap.Modal(document.getElementById('taskModal'));
            modal.show();
        }
    </script>
</body>
</html>
