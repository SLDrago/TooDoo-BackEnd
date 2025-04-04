<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');;
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/get-categories', [CategoryController::class, 'getCategories']);
Route::post('/add-category', [CategoryController::class, 'addCategory']);

Route::get('/get-tasks', [TaskController::class, 'getTasks'])->middleware('auth:sanctum');
Route::post('/store-task', [TaskController::class, 'store'])->middleware('auth:sanctum');
Route::get('/show-task/{id}', [TaskController::class, 'show'])->middleware('auth:sanctum');
Route::post('/update-task', [TaskController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/delete-task/{id}', [TaskController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/toggle-complete', [TaskController::class, 'toggleTaskComplete'])->middleware('auth:sanctum');
