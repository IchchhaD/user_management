<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CommentsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request)
{
    return $request->user();
});

Route::middleware('api')->group(function ()
{
    // Auth routes
    Route::prefix('auth')->group(function ()
    {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Posts routes
    Route::get('/posts', [PostsController::class, 'getPosts']);
    Route::get('/posts/{id}', [PostsController::class, 'viewPost']);
    Route::post('/posts', [PostsController::class, 'createPosts']);
    Route::put('/posts/{id}', [PostsController::class, 'updatePosts']);
    Route::delete('/posts/{id}', [PostsController::class, 'deletePosts']);
    
    // Comments routes
    Route::get('/posts/{postId}/comments', [CommentsController::class, 'getComments'])->name('getComments');
    Route::post('/posts/{postId}/comments', [CommentsController::class, 'createComments'])->name('createComments');
    Route::put('/posts/{postId}/comments/{commentId}', [CommentsController::class, 'update'])->name('updateComments');
    Route::delete('/posts/{postId}/comments/{commentId}', [CommentsController::class, 'delete'])->name('deleteComments');
});

// Fallback route for unhandled requests
Route::fallback(function ()
{
    return response()->json([
        'status' => '404',
        'message' => 'Requested Url Not Found.'
    ], 404);
});
