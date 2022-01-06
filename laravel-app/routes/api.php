<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function() {
    /* Posts Route */
    Route::get('posts/', [PostController::class, 'list']);
    Route::get('posts/edit/{id}', [PostController::class, 'edit']);
    Route::get('posts/show/{id}', [PostController::class, 'show']);
    Route::put('posts/update/{id}', [PostController::class, 'update']);
    Route::delete('posts/destroy/{id}', [PostController::class, 'destroy']);

    /* Video Route */
    Route::get('videos/', [VideoController::class, 'index']);
    Route::get('videos/show/{id}', [VideoController::class, 'show']);

    /* Comments Route */
    Route::post('comment/store', [CommentController::class, 'store']);
    Route::post('reply/store', [CommentController::class, 'replyStore']);
});
