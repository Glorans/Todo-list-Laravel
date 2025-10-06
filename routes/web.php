<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::patch('/tasks/{id}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');