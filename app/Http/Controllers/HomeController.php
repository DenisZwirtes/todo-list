<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = request()->user();
        return Inertia::render('Home', [
            'stats' => [
                'total_tasks' => $user->tasks()->count(),
                'completed_tasks' => $user->tasks()->where('is_completed', true)->count(),
                'pending_tasks' => $user->tasks()->where('is_completed', false)->count(),
                'total_categories' => $user->categories()->count(),
            ]
        ]);
    }
}
