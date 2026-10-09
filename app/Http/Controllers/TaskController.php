<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::with('category');

        if ($request->has('status')) {
            $tasks->where('status', $request->status);
        }

        if ($request->has('category_id')) {
            $tasks->where('category_id', $request->category_id);
        }

        $tasks = $tasks->paginate(10);

        return response()->json([
            'message' => 'Daftar task berhasil diambil',
            'data' => $tasks
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:to-do,in-progress,done',
            'category_id' => 'required|exists:categories,id'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'to-do',
            'category_id' => $request->category_id
        ]);

        $task->load('category');

        return response()->json([
            'message' => 'Task berhasil dibuat',
            'data' => $task
        ], 201);
    }

    public function show(int $id)
    {
        $task = Task::with('category')->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail task berhasil diambil',
            'data' => $task
        ], 200);
    }

    public function update(Request $request, int $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:to-do,in-progress,done',
            'category_id' => 'sometimes|required|exists:categories,id'
        ]);

        $task->update($request->only([
            'title',
            'description',
            'status',
            'category_id'
        ]));

        $task->load('category');

        return response()->json([
            'message' => 'Task berhasil diperbarui',
            'data' => $task
        ], 200);
    }

    public function destroy(int $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task tidak ditemukan'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task berhasil dihapus'
        ], 200);
    }
}