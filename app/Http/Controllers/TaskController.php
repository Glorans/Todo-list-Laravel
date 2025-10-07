<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('created_at', 'desc')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'details' => 'nullable|max:500',
        ]);

        Task::create([
            'title' => $request->title,
            'details' => $request->details,
            'status' => 'todo'
        ]);

        return redirect()->back()->with('success', 'Task added successfully!');
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        $request->validate([
            'title' => 'required|max:255',
            'details' => 'nullable|max:500',
        ]);

        $task->update([
            'title' => $request->title,
            'details' => $request->details,
        ]);

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update(['status' => $request->status]);
        
        return redirect()->back();
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully!');
    }
}