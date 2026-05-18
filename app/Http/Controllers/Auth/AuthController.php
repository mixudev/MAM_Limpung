<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return $this->redirectToDashboard()->with('success', 'Selamat Datang Kembali.. | Semoga hari ini penuh semangat dan inspirasi..');
    }

    // -----------------------------------------------------------------------
    //  Handle Logout
    // -----------------------------------------------------------------------

    public function logout(Request $request): RedirectResponse
    {
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
}
