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
//public routes
Route::get('/getPosts', [PostsController::class, 'getPosts'])->name('getPosts');
Route::get('/viewPosts/{id}', [PostsController::class, 'viewPosts'])->name('viewPosts');
Route::get('/getPosts/{postId}/getComments', [CommentsController::class, 'getComments'])->name('getComments');

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
    Route::post('/createPosts', [PostsController::class, 'createPosts'])->name('createPosts');
    Route::put('/updatePosts/{id}', [PostsController::class, 'updatePosts'])->name('updatePosts');
    Route::delete('/deletePosts/{id}', [PostsController::class, 'deletePosts'])->name('deletePosts');
    Route::get('/getMyPosts', [PostsController::class, 'getMyPosts'])->name('getMyPosts');
    
    // Comments routes
    Route::post('/viewPosts/{postId}/createComments', [CommentsController::class, 'createComments'])->name('createComments');
    Route::put('/viewPosts/{postId}/updateComments/{commentId}', [CommentsController::class, 'updateComments'])->name('updateComments');
    Route::delete('/deleteComments/{id}', [CommentsController::class, 'deleteComments'])->name('deleteComments');
});

// Fallback route for unhandled requests
Route::fallback(function ()
{
    return response()->json([
        'status' => '404',
        'message' => 'Requested Url Not Found.'
    ], 404);
});
