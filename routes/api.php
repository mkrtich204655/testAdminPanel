<?php

use App\Enums\Roles;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthController::class)->group(function (){
    Route::post('register', 'registration');
    Route::match(['get', 'post'], 'login', 'login')->name('login');
});

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(['role:' . Roles::MANAGER->value])
        ->controller(ManagerController::class)
        ->prefix('employee')
        ->group(function (){
            Route::post('/', 'createEmployee');
            Route::get('/', 'getEmployees');
    });

    Route::controller(PostController::class)
        ->prefix('post')
        ->group(function (){
            Route::post('/', 'createPost')->middleware(['role:' . Roles::EMPLOYEE->value]);
            Route::get('/', 'getPosts');
    });

    Route::get('categories', [CategoryController::class, 'getCategories']);
});

