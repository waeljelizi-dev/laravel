<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController; 
//a simple closure route (good for quick testing)
Route::get('/', function () {
    return view('welcome');
});

//resource routes - one line of code to create all the 7 CRUD routes
Route::resource('posts', PostController::class);


Route::resource('dashboard', DashboardController::class);
//Named routes - you can give a name to a route to use that name in your code instead of the URL
Route::get('/about', function(){
    return view('about');
})->name('about');

