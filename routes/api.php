<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('image', [ImageController::class, 'index']);
Route::post('image', [ImageController::class, 'store']);
Route::delete('image', [ImageController::class, 'destroy']);