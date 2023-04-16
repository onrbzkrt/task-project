<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// or

// List
Route::get('/', 'App\Http\Controllers\TaskController@index');

// Add, Edit, Delete
Route::post('/tasks', 'App\Http\Controllers\TaskController@store')->name('tasks.store');
Route::delete('/tasks/{task}', 'App\Http\Controllers\TaskController@destroy')->name('tasks.destroy');
Route::put('/tasks/{task}', 'App\Http\Controllers\TaskController@update')->name('tasks.update');

// Reorder
Route::post('tasks/reorder', 'App\Http\Controllers\TaskController@reorder')->name('tasks.reorder');
