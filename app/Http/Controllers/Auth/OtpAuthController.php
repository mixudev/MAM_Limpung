<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RequestOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Mail\User\OtpLoginMail;
use App\Models\User;
use App\Services\SmtpService;
use App\Services\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OtpAuthController extends Controller
{
    /**
     * Show the request OTP form.
     */
    public function showRequestForm(): View
    {
        return view('auth.login-otp');
    }

    /**
     * Send OTP code to the user.
     */
    public function sendOtp(RequestOtpRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Check if user is active
            if (! $user->is_active) {
                return redirect()->route('login.otp.verify', ['email' => $request->email])
                    ->with('success', 'Kode OTP telah dikirimkan ke email Anda jika terdaftar di sistem kami.');
            }

            // Generate 6-digit OTP code
            $otpCode = sprintf('%06d', mt_rand(0, 999999));

            // Secure Hashing (SHA-256) of OTP code before storage (OWASP Top 10)
            DB::table('otp_codes')->updateOrInsert(
                ['email' => $user->email],
                [
                    'otp_code' => hash('sha256', $otpCode),
                    'attempts' => 0,
                    'expires_at' => now()->addMinutes(5),
                    'created_at' => now(),
                ]
            );

            // Send email using SmtpService
            app(SmtpService::class)->sendQuiet(
                new OtpLoginMail($user, $otpCode),
                $user->email,
                $user->name
            );

            SystemLogService::logSecurity('otp_requested', 'Pengguna meminta kode OTP untuk login', $user);
        }

        // Redirect to verify form with email and generic success message (OWASP Top 10)
        return redirect()->route('login.otp.verify', ['email' => $request->email])
            ->with('success', 'Kode OTP telah dikirimkan ke email Anda jika terdaftar di sistem kami.');
    }

    /**
     * Show the verify OTP form.
     */
    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        $email = $request->query('email');

        if (! $email) {
            return redirect()->route('login.otp')->with('error', 'Masukkan email Anda terlebih dahulu.');
        }

        return view('auth.verify-otp', [
            'email' => $email,
        ]);
    }

    /**
     * Verify the OTP code and log the user in.
     */
    public function verify(VerifyOtpRequest $request): RedirectResponse
    {
        $record = DB::table('otp_codes')
            ->where('email', $request->email)
            ->first();

        if (! $record) {
            return redirect()->route('login.otp')->with('error', 'Kode OTP tidak ditemukan atau telah kedaluwarsa. Silakan minta kode baru.');
        }

        if (now()->gt($record->expires_at)) {
            DB::table('otp_codes')->where('email', $request->email)->delete();
            return redirect()->route('login.otp')->with('error', 'Kode OTP telah kedaluwarsa. Silakan minta kode baru.');
        }

        // Increment attempts first (OWASP Top 10: Rate Limiting & Attempt Capping)
        DB::table('otp_codes')->where('email', $request->email)->increment('attempts');
        $currentAttempts = $record->attempts + 1;

        if ($currentAttempts >= 3) {
            DB::table('otp_codes')->where('email', $request->email)->delete();
            return redirect()->route('login.otp')->with('error', 'Terlalu banyak kegagalan verifikasi. Kode OTP dinonaktifkan demi alasan keamanan.');
        }

        // Compare OTP code hashes using timing-attack safe comparison (hash_equals)
        $inputHash = hash('sha256', $request->otp_code);

        if (hash_equals($record->otp_code, $inputHash)) {
            // Delete OTP record immediately upon success
            DB::table('otp_codes')->where('email', $request->email)->delete();

            $user = User::where('email', $request->email)->first();

            if (! $user || ! $user->is_active) {
                return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan atau tidak ditemukan.');
            }

            // Perform login
            Auth::login($user);

            // SECURITY: Regenerate session ID to prevent Session Fixation (OWASP Top 10)
            $request->session()->regenerate();

            // Record login metadata
            $user->recordLogin($request->ip());

            SystemLogService::logSecurity('login_otp_success', 'Pengguna login menggunakan OTP', $user);

            return redirect()->route($user->dashboardRoute())
                ->with('success', 'Selamat Datang Kembali.. | Login OTP Berhasil.');
        }

        $remaining = 3 - $currentAttempts;
        return redirect()->back()
            ->withInput()
            ->with('error', "Kode OTP salah. Sisa percobaan: {$remaining} kali.");
    }
}
