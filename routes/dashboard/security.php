<?php

use App\Http\Controllers\Dashboard\Security\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('super-admin')
    ->name('super-admin.')
    ->middleware(['auth', 'active', 'permission:access-super-admin-dashboard'])
    ->group(function () {
        Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
        Route::post('/roles', [RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::put('/roles/{role:name}', [RolePermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{role:name}', [RolePermissionController::class, 'destroyRole'])->name('roles.destroy');
    });
