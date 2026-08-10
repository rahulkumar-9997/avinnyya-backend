<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BlogController;

Route::get('blogs', [BlogController::class, 'blogList']);
Route::get('blogs/{slug}', [BlogController::class, 'blogDetails']);