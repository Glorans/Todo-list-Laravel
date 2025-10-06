@extends('layouts.app')

@section('content')
    <h1>Todo List</h1>
    <p class="subheading">One way to prevent procrastination</p>

    <div class="add-task-section">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <input type="text" class="add-task-input" name="title" placeholder="Enter a new task..." required>
            <div class="detail-and-button">
                <input type="text" class="add-task-detail-input" name="details" placeholder="Add details or target date...">
                <button type="submit" class="add-task-btn">Add Task</button>
            </div>
        </form>
    </div>

    <div class="status-column todo-list">
        <div class="column-header">
            <h2>Todo List</h2>
            <span class="task-counter">{{ $tasks->count() }} {{ $tasks->count() === 1 ? 'task' : 'tasks' }}</span>
        </div>
        <hr class="column-divider">

        @foreach($tasks as $task)
            <div class="card {{ $task->status }}">
                <div class="text-wrap">
                    <span class="task-text">{{ $task->title }}</span>
                    <small class="task-detail">{{ $task->details ?? 'No details added' }}</small>
                </div>
                <div class="icon-group">
                    <button class="edit-btn" title="Edit Task" onclick="openEditForm({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ addslashes($task->details ?? '') }}')">✎</button>
                    
                    <form action="{{ route('tasks.status', $task->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="inprogress">
                        <button type="submit" class="progress-btn" title="Mark as In Progress">⟳</button>
                    </form>
                    
                    <form action="{{ route('tasks.status', $task->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="done">
                        <button type="submit" class="move-btn" title="Mark as Done" {{ $task->status === 'done' ? 'disabled' : '' }}>></button>
                    </form>
                    
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this task?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" title="Delete Task">×</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="overlay" id="overlay" onclick="closeEditForm()"></div>
    <div class="edit-form" id="editForm">
        <h3>Edit Task</h3>
        <form id="editTaskForm" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="title" id="editTitle" placeholder="Task title" required>
            <textarea name="details" id="editDetails" placeholder="Task details" rows="3"></textarea>
            <button type="submit" class="save-btn">Save Changes</button>
            <button type="button" class="cancel-btn" onclick="closeEditForm()">Cancel</button>
        </form>
    </div>

    <script>
        function openEditForm(id, title, details) {
            document.getElementById('editForm').classList.add('active');
            document.getElementById('overlay').classList.add('active');
            document.getElementById('editTitle').value = title;
            document.getElementById('editDetails').value = details;
            document.getElementById('editTaskForm').action = `/tasks/${id}`;
        }

        function closeEditForm() {
            document.getElementById('editForm').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');
        }
    </script>
@endsection