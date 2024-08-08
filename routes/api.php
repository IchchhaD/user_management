<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CommentsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=>'api','prefix'=>'auth'], function($router)
{
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/getPosts', [CommentsController::class, 'getPosts']);

Route::get('/viewPost', [PostsController::class, 'viewPost']);

Route::group(['middleware'=>'api','prefix'=>'posts'], function($router)
{
    //Route::post('/create', [ ::class, 'create']);
    Route::put('/update', [PostsController::class, 'update']);
    Route::post('/delete', [PostsController::class, 'delete']);
});

Route::get('/getComments', [CommentsController::class, 'get']);

Route::group(['middleware'=>'api','prefix'=>'comments'], function($router)
{
    Route::post('/create', [CommentsController::class, 'create']);
    Route::put('/update', [CommentsController::class, 'update']);
    Route::post('/delete', [CommentsController::class, 'delete']);
});
