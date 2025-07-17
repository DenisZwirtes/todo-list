<?php

namespace App\Http\Controllers;

use App\Contracts\Services\TaskServiceInterface;
use App\Contracts\Services\LogServiceInterface;
use App\DTOs\TaskDTO;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use App\Support\Logging\HasFluentLogging;
use App\Enums\LogOperation;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    use HasFluentLogging;

    protected $taskService;
    protected $logService;

    public function __construct(TaskServiceInterface $taskService, LogServiceInterface $logService)
    {
        $this->taskService = $taskService;
        $this->logService = $logService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['category_id', 'is_completed', 'search']);

        $tasks = $this->taskService->listUserTasks($filters);
        $categories = Category::where('user_id', auth()->id())->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks->items(),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ],
            'filters' => $filters,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $categories = Category::where('user_id', auth()->id())->get();
        $users = User::all(['id', 'name', 'email']);

        return Inertia::render('Tasks/Create', [
            'categories' => $categories,
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(TaskDTO::rules(), TaskDTO::messages());

            $taskDTO = TaskDTO::fromValidated($validated);
            $task = $this->taskService->create($taskDTO);

            $this->logCreate('Task', $task->id, [
                'title' => $task->title,
                'status' => $task->status,
                'priority' => $task->priority
            ]);

            return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
        } catch (Exception $e) {
            $this->logErrorWithRequest('Task', LogOperation::CREATE, $request, $e, [
                'validation_data' => $request->all()
            ]);

            return back()->withErrors(['error' => 'Erro ao criar tarefa: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): Response
    {
        $task = $this->taskService->findById($task->id);

        if (!$task) {
            abort(404);
        }

        return Inertia::render('Tasks/Show', [
            'task' => $task
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): Response
    {
        $task = $this->taskService->findById($task->id);

        if (!$task) {
            abort(404);
        }

        $categories = Category::where('user_id', auth()->id())->get();
        $users = User::all(['id', 'name', 'email']);

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'categories' => $categories,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        try {
            $validated = $request->validate(
                TaskDTO::updateRules($task->id),
                TaskDTO::messages()
            );

            $taskDTO = TaskDTO::fromValidated($validated);
            $updatedTask = $this->taskService->update($task->id, $taskDTO);

            $this->logUpdate('Task', $task->id, [
                'title' => $updatedTask->title,
                'status' => $updatedTask->status,
                'priority' => $updatedTask->priority,
                'changes' => $updatedTask->getChanges()
            ]);

            return redirect('/tasks')->with('success', 'Tarefa atualizada com sucesso!')->setStatusCode(303);
        } catch (Exception $e) {
            $this->logErrorWithRequest('Task', LogOperation::UPDATE, $request, $e, [
                'task_id' => $task->id,
                'validation_data' => $request->all()
            ]);

            return back()->withErrors(['error' => 'Erro ao atualizar tarefa: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $taskId = $task->id;
            $taskTitle = $task->title;

            $this->taskService->delete($taskId);

            $this->logDelete('Task', $taskId, [
                'title' => $taskTitle,
                'status' => $task->status,
                'priority' => $task->priority
            ]);

            return redirect()->route('tasks.index')->with('success', 'Tarefa excluída com sucesso!')->setStatusCode(303);
        } catch (Exception $e) {
            $this->logError('Task', LogOperation::DELETE, $e, [
                'task_id' => $task->id,
                'title' => $task->title
            ]);

            return back()->withErrors(['error' => 'Erro ao excluir tarefa: ' . $e->getMessage()]);
        }
    }

    public function toggleCompleted(Task $task)
    {
        $task->is_completed = !$task->is_completed;
        $task->save();

        return redirect('/tasks')->with('success', 'Status da tarefa atualizado!')->setStatusCode(303);
    }
}
