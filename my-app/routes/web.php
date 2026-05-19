<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

//a simple closure route (good for quick testing)
Route::get('/', function(){
    return view('welcome');
});

//resource routes - one line of code to create all the 7 CRUD routes
Route::resource('posts', PostController::class);

//Named routes - you can give a name to a route and use that name in your code instead of the URL
Route::get('/about', function(){
    return view('about');
})->name('about');


