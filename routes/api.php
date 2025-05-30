
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/tasks', [TaskController::class, 'store']);
Route::middleware('auth:sanctum')->get('/tasks', [TaskController::class, 'index']);
Route::middleware('auth:sanctum')->delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::middleware('auth:sanctum')->patch('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
Route::middleware('auth:sanctum')->put('/tasks/{task}', [TaskController::class, 'update']);




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('tasks', TaskController::class);
});
