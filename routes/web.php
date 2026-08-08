<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\CkeditorController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CacheController;
use App\Http\Controllers\Backend\BlogController;

Route::get('/', [AuthController::class, 'showLoginForm']);
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('forget/password', [AuthController::class, 'showForgetPasswordForm'])->name('forget.password');
    Route::post('forget.password', [AuthController::class, 'submitForgetPasswordForm'])->name('forget.password.submit');

    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::group(['middleware' => ['auth:web']], function() {

    Route::post('ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');
    Route::get('ckeditor/images', [CkeditorController::class, 'imageList'])->name('ckeditor.images');
    Route::delete('ckeditor/image', [CkeditorController::class, 'deleteImage'])->name('ckeditor.delete');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('clear-cache', [CacheController::class, 'clearCache'])->name('clear-cache');
    Route::resource('profile', ProfileController::class);
    Route::resource('manage-blog', BlogController::class)->parameters(['manage-blog' => 'blog']);

    Route::delete('/manage-blog/gallery-image/{image}', [BlogController::class, 'destroyGalleryImage'])
    ->name('manage-blog.gallery-image.destroy');
    
});
