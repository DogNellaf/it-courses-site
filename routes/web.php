<?php

use App\Http\Controllers\CoursesController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// Публичные страницы
Route::get('/', [CoursesController::class, 'index'])->name('index');
Route::post('/applications', [CoursesController::class, 'storeApplication'])->name('application.store');
Route::get('/courses/{course}', [CoursesController::class, 'detail'])->name('detail');

// Личный кабинет (требует авторизации)
Route::middleware('auth')->prefix('home')->name('home.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/courses/create', [HomeController::class, 'create'])->name('course.create');
    Route::post('/courses', [HomeController::class, 'store'])->name('course.store');
    Route::delete('/applications/{application}', [HomeController::class, 'destroyApplication'])->name('application.destroy');
});

// Алиас для совместимости с navbar
Route::permanentRedirect('/home', '/home/');
