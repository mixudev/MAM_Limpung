<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Semua route API dilindungi Sanctum token (kecuali login).
| Response selalu JSON — tidak ada redirect.
|
*/

// ============================================================================
//  PUBLIC API — Tidak butuh token
// ============================================================================

Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('/login', [ApiAuthController::class, 'login'])->name('login')->middleware('throttle:api-login');
});

// ============================================================================
//  PROTECTED API — Butuh token Sanctum yang valid
// ============================================================================

Route::middleware(['auth:sanctum', 'active'])->group(function () {

    // --- Auth management ---
    Route::prefix('auth')->name('api.auth.')->group(function () {
        Route::get('/me', [ApiAuthController::class, 'me'])->name('me');
        Route::get('/tokens', [ApiAuthController::class, 'tokens'])->name('tokens');
        Route::delete('/logout', [ApiAuthController::class, 'logout'])->name('logout');
        Route::delete('/logout-all', [ApiAuthController::class, 'logoutAll'])->name('logout-all');
        Route::delete('/logout-others', [ApiAuthController::class, 'logoutOthers'])->name('logout-others');
    });

    // --- User resource (semua CRUD dalam 1 grup) ---
    Route::prefix('users')->name('api.users.')->group(function () {
        Route::get('/', fn () => response()->json(['message' => 'User list endpoint']))
            ->middleware('permission:view-users')
            ->name('index');

        Route::get('/{user}', fn () => response()->json(['message' => 'User detail endpoint']))
            ->middleware('permission:view-users')
            ->name('show');

        Route::post('/', fn () => response()->json(['message' => 'Create user endpoint']))
            ->middleware('permission:create-users')
            ->name('store');

        Route::put('/{user}', fn () => response()->json(['message' => 'Update user endpoint']))
            ->middleware('permission:edit-users')
            ->name('update');

        Route::delete('/{user}', fn () => response()->json(['message' => 'Delete user endpoint']))
            ->middleware('permission:delete-users')
            ->name('destroy');
    });

    // --- Super Admin & Admin only ---
    Route::prefix('admin')->name('api.admin.')->middleware('role:super-admin|admin')->group(function () {
        Route::get('/stats', fn () => response()->json([
            'users' => User::count(),
            'active' => User::active()->count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
        ]))->name('stats');
    });
});
