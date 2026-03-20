<!DOCTYPE html>
<html>
<head>
<title>Laravel Todo App</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f4f4f4; }
.todo-box { max-width: 700px; margin: 60px auto; }
.task-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-bottom: 1px solid #e9ecef;
}
.task-row:last-child { border-bottom: none; }
.view-mode {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
}
.task-title { flex: 1; font-size: 15px; }
.completed { text-decoration: line-through; color: #aaa; }
.edit-mode { width: 100%; }
</style>
</head>

<body>
<div class="container">
<div class="card todo-box shadow">
<div class="card-body">

    <div class="d-flex align-items-center justify-content-between mb-4">
    <h3 class="mb-0">To Do List</h3>
    <div class="d-flex gap-2">
        <span class="badge bg-primary fs-6">Total: {{ $totalTasks }}</span>
        <span class="badge bg-danger fs-6">Pending: {{ $pendingTasks }}</span>
        <span class="badge bg-success fs-6">Completed: {{ $completedTasks }}</span>
    </div>
</div>

    @if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form action="/task" method="POST" class="d-flex mb-4">
        @csrf
        <input type="text" name="title" class="form-control me-2" placeholder="Enter task">
        <button class="btn btn-success">Add</button>
    </form>

    <div class="list-group">
    @foreach($tasks as $task)
    <div class="task-row" id="task-{{ $task->id }}">

        {{-- View mode --}}
        {{-- View mode --}}
<div class="view-mode" id="view-{{ $task->id }}">

    <form action="/task/{{ $task->id }}/complete" method="POST" style="display:inline">
        @csrf @method('PATCH')
        <input type="checkbox" {{ $task->status ? 'checked' : '' }}
            onchange="this.form.submit()">
    </form>

    <span class="task-title {{ $task->status ? 'completed' : '' }}">
        {{ $task->title }}
    </span>

    <button onclick="showEdit({{ $task->id }})" class="btn btn-warning btn-sm">Edit</button>

    <form action="/task/{{ $task->id }}" method="POST" style="display:inline">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
    </form>

</div>

        {{-- Inline edit mode --}}
        <div class="edit-mode" id="edit-{{ $task->id }}" style="display:none;">
            <form action="/task/{{ $task->id }}" method="POST" class="d-flex gap-2">
                @csrf @method('PUT')
                <input type="text" name="title" value="{{ $task->title }}"
                    class="form-control" required>
                <button type="submit" class="btn btn-success btn-sm">Update</button>
                <button type="button" onclick="hideEdit({{ $task->id }})"
                    class="btn btn-secondary btn-sm">Cancel</button>
            </form>
        </div>

    </div>
    @endforeach
    </div>

</div>
</div>
</div>

<script>
function showEdit(id) {
    document.getElementById('view-' + id).style.display = 'none';
    document.getElementById('edit-' + id).style.display = 'block';
    document.querySelector('#edit-' + id + ' input[name="title"]').focus();
}

function hideEdit(id) {
    document.getElementById('view-' + id).style.display = 'flex';
    document.getElementById('edit-' + id).style.display = 'none';
}
</script>
{{-- Toast notification --}}
@if(session('success') || session('warning'))
<div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999; margin-top: 20px;">
    <div id="liveToast" class="toast align-items-center text-white border-0 show
        {{ session('warning') ? 'bg-danger' : 'bg-success' }}" role="alert">
        <div class="d-flex">
            <div class="toast-body fs-6">
                {{ session('warning') ? '⚠️ ' . session('warning') : '✅ ' . session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto hide toast after 3 seconds
var toast = document.getElementById('liveToast');
if (toast) {
    setTimeout(function() {
        var bsToast = new bootstrap.Toast(toast);
        bsToast.hide();
    }, 3000);
}

function showEdit(id) {
    document.getElementById('view-' + id).style.display = 'none';
    document.getElementById('edit-' + id).style.display = 'block';
    document.querySelector('#edit-' + id + ' input[name="title"]').focus();
}

function hideEdit(id) {
    document.getElementById('view-' + id).style.display = 'flex';
    document.getElementById('edit-' + id).style.display = 'none';
}
</script>
</body>
</html>