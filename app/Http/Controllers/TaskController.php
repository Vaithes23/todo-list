<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    
public function index()
{
    $tasks = Task::orderBy('status', 'asc')
                 ->orderBy('created_at', 'desc')
                 ->get();

    $totalTasks = Task::count();
    $pendingTasks = Task::where('status', 0)->count();
    $completedTasks = Task::where('status', 1)->count();

    return view('tasks.index', compact('tasks', 'totalTasks', 'pendingTasks', 'completedTasks'));
}
   


public function store(Request $request)
{
    $request->validate(['title' => 'required'], [
        'title.required' => 'Task cannot be empty. Please enter a task.'
    ]);

    $exists = Task::whereRaw('LOWER(title) = ?', [strtolower($request->title)])->exists();

    if ($exists) {
        return redirect('/')->with('warning', 'Task already exists!');
    }

    Task::create(['title' => $request->title]);
    return redirect('/')->with('success', 'Task added successfully!');
}

public function update(Request $request, $id)
{
    $request->validate(['title' => 'required'], [
        'title.required' => 'Task cannot be empty. Please enter a task.'
    ]);

    $exists = Task::whereRaw('LOWER(title) = ?', [strtolower($request->title)])
                  ->where('id', '!=', $id)
                  ->exists();

    if ($exists) {
        return redirect('/')->with('warning', 'Task already exists!');
    }

    $task = Task::find($id);
    $task->title = $request->title;
    $task->save();
    return redirect('/')->with('success', 'Task updated successfully!');
}

  public function destroy($id)
{
    Task::find($id)->delete();
    return redirect('/')->with('success', 'Task deleted successfully!');
}

public function complete($id)
{
    $task = Task::find($id);
    $task->status = !$task->status;
    $task->save();
    return redirect('/')->with('success', $task->status ? 'Task completed!' : 'Task marked incomplete!');
}
}