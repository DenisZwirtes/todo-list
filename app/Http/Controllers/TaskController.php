<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $query = auth()->user()->tasks()->with('category');

        if ($request->filled('category_id'))
            $query->where('category_id', $request->category_id);

        if ($request->boolean('completed'))
            $query->where('is_completed', true);

        $tasks = $query->latest()->get();
        $categories = auth()->user()->categories;

        return view('tasks.index', compact('tasks', 'categories'));
    }


    public function create()
    {
        $categories = Category::all();
        $users = User::all();

        $noCategories = $categories->isEmpty();

        return view('tasks.create', compact('categories', 'users', 'noCategories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'is_completed' => $request->boolean('is_completed'),
        ]);

        $task->users()->attach($request->users);

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso.');
    }


    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }


    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $categories = Category::all();
        $users = User::all();
        $selectedUsers = $task->users()->pluck('user_id')->toArray();

        return view('tasks.edit', compact('task', 'categories', 'users', 'selectedUsers'));
    }



    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'is_completed' => $request->boolean('is_completed'),
        ]);

        $task->users()->sync($validated['users']);

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso.');
    }


    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')
                         ->with('success', __('messages.task_deleted'));
    }
}
