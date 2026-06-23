<?php

use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\Dashboard\TeacherCategoryController;
use App\Http\Controllers\Dashboard\TeacherController;
use Illuminate\Support\Facades\Route;

// Teacher Categories
Route::prefix('admin/teacher-categories')
    ->name('admin.teacher-categories.')
    ->middleware('permission:access-admin-dashboard|access-super-admin-dashboard')
    ->group(function () {
        Route::get('/', [TeacherCategoryController::class, 'index'])->name('index');
        Route::post('/', [TeacherCategoryController::class, 'store'])->name('store');
        Route::put('/{teacherCategory}', [TeacherCategoryController::class, 'update'])->name('update');
        Route::delete('/{teacherCategory}', [TeacherCategoryController::class, 'destroy'])->name('destroy');
    });

// Teachers
Route::prefix('admin/teachers')
    ->name('admin.teachers.')
    ->middleware('permission:access-admin-dashboard|access-super-admin-dashboard')
    ->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('index');
        Route::get('/create', [TeacherController::class, 'create'])->name('create');
        Route::post('/', [TeacherController::class, 'store'])->name('store');
        Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('edit');
        Route::put('/{teacher}', [TeacherController::class, 'update'])->name('update');
        Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('destroy');
    });

// Students
Route::prefix('admin/students')
    ->name('admin.students.')
    ->middleware('permission:access-admin-dashboard|access-super-admin-dashboard')
    ->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/create', [StudentController::class, 'create'])->name('create');
        Route::post('/', [StudentController::class, 'store'])->name('store');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::put('/{student}', [StudentController::class, 'update'])->name('update');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
    });
