<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Comment\CommentController;
use App\Http\Controllers\API\Post\PostExportController;
use App\Http\Controllers\API\Post\PostImportController;
use App\Http\Controllers\API\Post\PostController;
use App\Http\Controllers\API\Video\VideoController;
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

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function () {
    /* Posts Route */
    Route::get('posts/', [PostController::class, 'list']);
    Route::get('posts/edit/{id}', [PostController::class, 'edit'])->middleware('can:edit posts');
    Route::get('posts/show/{id}', [PostController::class, 'show'])->middleware('can:show posts');
    Route::put('posts/update/{id}', [PostController::class, 'update']);
    Route::delete('posts/destroy/{id}', [PostController::class, 'destroy'])->middleware('can:delete posts');

    /* Video Route */
    Route::get('videos/', [VideoController::class, 'list']);
    Route::get('videos/show/{id}', [VideoController::class, 'show']);

    /* Comments Route */
    Route::post('comment/store', [CommentController::class, 'store']);

    /* Export Import Route */
    Route::get('posts/export/', [PostExportController::class, 'postExport']);
    Route::get('posts/export-status', [PostExportController::class, 'status']);

    Route::post('posts/import', [PostImportController::class, 'import']);
    Route::get('posts/import-status', [PostImportController::class, 'status']);
});
