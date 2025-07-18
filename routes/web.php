<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ExampleController;


// Rotas de autenticação customizadas para Inertia
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Rotas de registro
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Rotas de confirmação de senha
Route::get('/password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('/password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'confirm']);

// Rotas de reset de senha
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Rotas de verificação de email
Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/verification-notification', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');

Route::get('/', function () {
    return redirect('/login');
});


Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('tasks', TaskController::class);
    Route::put('/tasks/{task}/toggle', [TaskController::class, 'toggleCompleted'])->name('tasks.toggle');
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/create-ajax', [CategoryController::class, 'createAjax']);

    // Rotas de Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/export', [LogController::class, 'export'])->name('logs.export');
    Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');
    Route::get('/logs/{log}', [LogController::class, 'show'])->name('logs.show');

    // Rotas de Exemplo
    Route::post('/example/direct-usage', [ExampleController::class, 'exampleDirectUsage'])->name('example.direct-usage');
    Route::post('/example/helper', [ExampleController::class, 'exampleWithHelper'])->name('example.helper');
    Route::post('/example/validation', [ExampleController::class, 'exampleValidation'])->name('example.validation');
    Route::post('/example/listing', [ExampleController::class, 'exampleListing'])->name('example.listing');
    Route::post('/example/chained', [ExampleController::class, 'exampleChained'])->name('example.chained');
});
