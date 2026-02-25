<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    UserController,
    ProjectController,
    TaskController,
    DashboardController,
    CommentController,
    AuthController,
    ActivityController,
    TeamController
};

// V1
Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // Users
        Route::get('me', [UserController::class, 'me']);
        Route::apiResource('users', UserController::class)->only(['index', 'show', 'update']);

        // Teams
        Route::apiResource('teams', TeamController::class)->only(['index', 'store', 'show']);
        Route::post('teams/{team}/members', [TeamController::class, 'addMember']);
        
        // Projects
        Route::apiResource('projects', ProjectController::class);
        Route::prefix('projects/{project}')->group(function () {
            Route::get('activities', [ActivityController::class, 'index']);
            Route::get('stats', [DashboardController::class, 'index']);
        });
        
        // Tasks
        Route::apiResource('tasks', TaskController::class);
        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);
        
        // Task comments
        Route::apiResource('tasks.comments', CommentController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});