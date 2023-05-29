<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ToDoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// user registration routes
Route::post('register', [AuthController::class, 'register']);

// user login routes
Route::post('login', [AuthController::class, 'login']);


Route::get('all-article', [ArticleController::class, 'allArticle']);


Route::group(["middleware" => ["auth:api"]], function(){
    Route::group(["middleware" => ["isUser"]], function(){
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);

        // article routes

        Route::group(['prefix'=> "article"], function(){
            Route::get('list', [ArticleController::class, 'index']);
            Route::post('store', [ArticleController::class, 'store']);
            Route::post('edit', [ArticleController::class, 'update']);
            Route::get('delete/{id}', [ArticleController::class, 'delete']);
        });

        Route::group(['prefix'=> "todo"], function(){
            Route::get('/', [ToDoController::class, 'index']);
            Route::post('store', [ToDoController::class, 'store']);
            Route::post('edit', [ToDoController::class, 'update']);
            Route::get('delete/{id}', [ToDoController::class, 'delete']);
        });
    });
});
