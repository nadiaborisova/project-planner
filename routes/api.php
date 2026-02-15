<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\DashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);
    
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);
    Route::get('projects/{project}/activities', [ProjectController::class, 'activities']);
    Route::get('projects/{project}/stats', [DashboardController::class, 'index']);
    Route::post('tasks/{task}/comments', [TaskController::class, 'addComment']);
});