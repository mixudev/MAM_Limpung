<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Handles web (session-based) authentication.
 *
 * Flow:
 *   1. User submits credentials via POST /login
 *   2. LoginRequest validates + rate-limits + authenticates
 *   3. Session is regenerated to prevent session fixation
 *   4. User is redirected to their role-specific dashboard
 */
class AuthController extends Controller
{
    // -----------------------------------------------------------------------
    //  Show Login Form
    // -----------------------------------------------------------------------

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        return view('auth.login');
    }

    // -----------------------------------------------------------------------
    //  Handle Login
    // -----------------------------------------------------------------------

    public function login(LoginRequest $request): RedirectResponse
    {
        // Authenticate (throws ValidationException on failure)
        $request->authenticate();

        // SECURITY: Regenerate session ID to prevent session fixation attacks
        $request->session()->regenerate();

        // Record login metadata
        Auth::user()->recordLogin($request->ip());

        SystemLogService::logSecurity('login_success', 'Pengguna berhasil masuk ke sistem', Auth::user());

        return $this->redirectToDashboard()->with('success', 'Selamat Datang Kembali.. | Semoga hari ini penuh semangat dan inspirasi..');
    }

    // -----------------------------------------------------------------------
    //  Handle Logout
    // -----------------------------------------------------------------------

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            SystemLogService::logSecurity('logout', 'Pengguna logout dari sistem', $user);
        }

        Auth::guard('web')->logout();

        // SECURITY: Invalidate and regenerate token (CSRF) to prevent replay
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda telah berhasil logout.');
    }

    // -----------------------------------------------------------------------
    //  Role-Based Redirect
    // -----------------------------------------------------------------------

    protected function redirectToDashboard(): RedirectResponse
    {
        $route = Auth::user()->dashboardRoute();

        return redirect()->route($route);
    }

    /**
     * Verify email address of the user.
     */
    public function verifyEmail(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, hash('sha256', $user->email))) {
            abort(403, 'Tanda tangan hash tidak valid.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            // Log security event
            SystemLogService::logSecurity('email_verified', 'Alamat email pengguna telah berhasil diverifikasi', $user);
        }

        return redirect()->route('login')->with('success', 'Email Berhasil Diverifikasi! | Silakan login ke akun Anda.');
    }

    /**
     * Show direct password reset form.
     */
    public function showDirectResetPassword(Request $request, $uuid, $token): View
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        // Check if token exists and is valid in database
        $record = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();

        if (! $record || ! Hash::check($token, $record->token) || now()->subHours(2)->gt($record->created_at)) {
            abort(403, 'Tautan reset password tidak valid atau telah kedaluwarsa.');
        }

        return view('auth.reset-password-direct', [
            'uuid' => $uuid,
            'token' => $token,
            'email' => $user->email,
        ]);
    }

    /**
     * Handle direct password reset form submission.
     */
    public function handleDirectResetPassword(Request $request, $uuid, $token): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = User::where('uuid', $uuid)->firstOrFail();

        $record = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();

        if (! $record || ! Hash::check($token, $record->token) || now()->subHours(2)->gt($record->created_at)) {
            abort(403, 'Tautan reset password tidak valid atau telah kedaluwarsa.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete token to invalidate link
        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->delete();

        // Log security event
        SystemLogService::logSecurity('password_reset_success', 'Kata sandi berhasil direset melalui link sekali pakai', $user);

        return redirect()->route('login')->with('success', 'Password Berhasil Direset! | Silakan login menggunakan password baru Anda.');
    }
}
