<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware('auth')->group(function () {
    // Página inicial após login
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // CRUD de Tarefas
    Route::resource('tasks', TaskController::class);

    // CRUD de Categorias
    Route::resource('categories', CategoryController::class);
});
