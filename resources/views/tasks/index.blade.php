@extends('layouts.app')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: space-between; margin-top: 10px; margin-bottom: 20px;">
        <h1 style="font-size: 2rem"><b><u>Task List</u></b></h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create Task</a>
    </div>

    <!-- Filter and Sort Form -->
    <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
        <div class="row g-3">
            <!-- Filter by Status -->
            <div class="col-md-4">
                <label for="status" class="form-label">Filter by Status:</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <!-- Sort by Due Date -->
            <div class="col-md-4">
                <label for="sort" class="form-label">Sort by Due Date:</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Apply</button>
            </div>
        </div>
    </form>

    <!-- Tasks Table -->
    @if ($tasks->isEmpty())
        <p>No tasks available. Create a new one!</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Posted By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->due_date ?? 'No Due Date' }}</td>
                        <td>{{ $task->user->name ?? 'Unknown' }}</td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="viewTask({{ $task }})">View</button>
                            @if($task->user_id == auth()->user()->id)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Pagination Links -->
    <div class="mt-3">
        {{ $tasks->links() }}
    </div>
</div>

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

@endsection
