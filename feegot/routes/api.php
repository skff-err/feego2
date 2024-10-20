<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassroomController;

Route::get('/classes/{year}', [ClassroomController::class, 'getClassesByYear']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
