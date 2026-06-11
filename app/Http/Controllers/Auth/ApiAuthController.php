<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApiLoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Handles API (token-based) authentication via Laravel Sanctum.
 *
 * Endpoints:
 *   POST   /api/auth/login    → Issue token
 *   DELETE /api/auth/logout   → Revoke current token
 *   DELETE /api/auth/logout-all → Revoke all tokens
 *   GET    /api/auth/me       → Current user info
 *   GET    /api/auth/tokens   → List active tokens
 */
class ApiAuthController extends Controller
{
    // -----------------------------------------------------------------------
    //  Issue Token (Login)
    // -----------------------------------------------------------------------

    public function login(ApiLoginRequest $request): JsonResponse
    {
        $request->ensureIsNotRateLimited();

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            // SECURITY: Hit rate limiter on every failed attempt.
            $request->recordFailedAttempt();

            throw ValidationException::withMessages([
                'email' => ['Kredensial tidak valid.'],
            ]);
        }

        if (! $user->is_active) {
            $request->recordFailedAttempt();

            return response()->json([
                'message' => 'Akun Anda telah dinonaktifkan.',
            ], 403);
        }

        // SECURITY: Abilities determined server-side based on user roles.
        // Never trust client-supplied ability claims.
        $abilities = $this->resolveAbilities($user);

        // Create token with 24-hour expiry.
        $token = $user->issueToken($request->device_name, $abilities, expiresInMinutes: 60 * 24);

        $user->recordLogin($request->ip());

        // Clear rate limiter on success.
        $request->clearRateLimit();

        return response()->json([
            'message' => 'Login berhasil.',
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => now()->addDay()->toISOString(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
            ],
        ]);
    }

    /**
     * Resolve Sanctum token abilities dari role user.
     * Mencegah client self-escalate permission token mereka.
     * Tidak menggunakan wildcard ['*'] karena token bisa bocor.
     *
     * @return array<string>
     */
    protected function resolveAbilities(User $user): array
    {
        $roleAbilities = [
            'super-admin' => ['read', 'write', 'manage-users', 'manage-system', 'manage-backup', 'manage-security'],
            'admin' => ['read', 'write', 'manage-users'],
            'guru' => ['read', 'write:articles', 'write:galeri', 'write:prestasi'],
            'siswa' => ['read', 'write:own-articles', 'write:own-galeri'],
        ];

        foreach ($roleAbilities as $role => $abilities) {
            if ($user->hasRole($role)) {
                return $abilities;
            }
        }

        // Default: read-only untuk role tidak dikenal.
        return ['read'];
    }

    // -----------------------------------------------------------------------
    //  Revoke Current Token (Logout)
    // -----------------------------------------------------------------------

    public function logout(Request $request): JsonResponse
    {
        // currentAccessToken() is only available for API guard
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Token berhasil dicabut.']);
    }

    // -----------------------------------------------------------------------
    //  Revoke All Tokens (Logout Everywhere)
    // -----------------------------------------------------------------------

    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->revokeAllTokens();

        return response()->json(['message' => 'Semua token berhasil dicabut.']);
    }

    // -----------------------------------------------------------------------
    //  Revoke Other Tokens (Keep Current Session)
    // -----------------------------------------------------------------------

    public function logoutOthers(Request $request): JsonResponse
    {
        $currentTokenId = $request->user()->currentAccessToken()->id;
        $request->user()->revokeOtherTokens($currentTokenId);

        return response()->json(['message' => 'Sesi lain berhasil dicabut.']);
    }

    // -----------------------------------------------------------------------
    //  Current Authenticated User
    // -----------------------------------------------------------------------

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('roles');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'last_login' => $user->last_login_at?->toISOString(),
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    // -----------------------------------------------------------------------
    //  List Active Tokens
    // -----------------------------------------------------------------------

    public function tokens(Request $request): JsonResponse
    {
        $tokens = $request->user()->tokens()
            ->whereNull('expires_at')
            ->orWhere('expires_at', '>', now())
            ->get(['id', 'name', 'abilities', 'last_used_at', 'expires_at', 'created_at']);

        return response()->json(['tokens' => $tokens]);
    }
}
