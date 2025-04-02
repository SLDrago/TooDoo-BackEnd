<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'status' => 'required|string|in:pending,completed',
            'priority' => 'required|string|in:low,medium,high',
            'due_date' => 'nullable|date',
            'user' => 'required|exists:users,id',
        ]);

        $task = Task::create($validatedData);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task,
        ], 201);
    }

    public function show(Request $request)
    {
        $task = Task::find($request->id);
        if ($task) {
            return response()->json([
                'message' => 'Task found',
                'task' => $task,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }
    }
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:tasks,id',
            'task' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:20',
            'status' => 'sometimes|required|string|in:pending,completed',
            'priority' => 'sometimes|required|string|in:low,medium,high',
            'due_date' => 'sometimes|nullable|date',
        ]);

        $task = Task::find($validatedData['id']);
        $task->update($validatedData);

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task,
        ], 200);
    }

    public function destroy(Request $request)
    {
        $task = Task::find($request->id);
        if ($task) {
            $task->delete();
            return response()->json([
                'message' => 'Task deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }
    }

    public function search(Request $request)
    {
        $query = Task::query();

        if ($request->has('search')) {
            $query->where('task', 'like', '%' . $request->search . '%');
        }

        $tasks = $query->get();

        return response()->json([
            'message' => 'Tasks found',
            'tasks' => $tasks,
        ], 200);
    }
    public function filter(Request $request)
    {
        $query = Task::query();

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $tasks = $query->get();

        return response()->json([
            'message' => 'Tasks found',
            'tasks' => $tasks,
        ], 200);
    }
}
