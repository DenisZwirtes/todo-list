<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())
                     ->latest()
                     ->get()
                     ->load('category');


        return view('tasks.index', compact('tasks'));
    }



    public function create()
    {
        $categories = auth()->user()
                            ->categories ?? [];

        return view('tasks.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        auth()->user()->tasks()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'is_completed' => $request->has('is_completed'), // Converte "on" para true (1)
        ]);

        return redirect()->route('tasks.index')
                         ->with('success', __('messages.task_created'));
    }



    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }


    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $categories = auth()->user()
                            ->categories ?? [];

        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $task->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'is_completed' => $request->has('is_completed'),
        ]);

        return redirect()->route('tasks.show', $task)
                         ->with('success', __('messages.task_updated'));
    }




    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')
                         ->with('success', __('messages.task_deleted'));
    }
}
