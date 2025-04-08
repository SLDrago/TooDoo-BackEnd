<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getTasks(Request $request)
    {
        $tasks = Task::where('user_id', $request->user()->id)
            ->get(['id', 'title', 'task', 'category_id', 'is_complete', 'priority', 'due_date']);
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:30',
            'task' => 'required|string|max:100',
            'category_id' => 'required|string|max:255',
            'is_complete' => 'nullable|boolean',
            'priority' => 'required|string|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $validatedData['user_id'] = $request->user()->id;

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
            'title' => 'sometimes|required|string|max:30',
            'task' => 'sometimes|required|string|max:100',
            'category_id' => 'sometimes|required|integer|max:20',
            'is_complete' => 'sometimes|required|boolean',
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

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category);
        }

        $tasks = $query->get();

        return response()->json([
            'message' => 'Tasks found',
            'tasks' => $tasks,
        ], 200);
    }

    public function toggleTaskComplete(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:tasks,id',
        ]);

        $task = Task::find($validatedData['id']);
        $task->update(['is_complete' => !$task->is_complete]);

        return response()->json([
            'message' => 'Task completion status toggled successfully',
            'task' => $task,
        ], 200);
    }
}
