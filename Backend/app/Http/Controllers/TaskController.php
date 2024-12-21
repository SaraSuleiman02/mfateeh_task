<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Create a new task
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task = Task::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Task Added successfully',
            'task' => $task,
        ], 201);
    }

    // Get all tasks for the authenticated user
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->with('user:id,name')->get();

        return response()->json($tasks);
    }

    // Update an existing task
    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Task Updated successfully',
            'task' => $task,
        ]);
    }

    // Delete a task
    public function destroy(Request $request, $id)
    {
        $task = Task::where('user_id', $request->user()->id)->findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
