<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\User\ForgotPasswordMail;
use App\Models\User;
use App\Services\SmtpService;
use App\Services\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password link request form.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Check if user is active
            if (! $user->is_active) {
                return redirect()->back()->with('success', 'Tautan untuk mengatur ulang kata sandi telah dikirim ke email Anda jika terdaftar di sistem kami.');
            }

            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            $resetUrl = route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);

            app(SmtpService::class)->sendQuiet(
                new ForgotPasswordMail($user, $resetUrl),
                $user->email,
                $user->name
            );

            SystemLogService::logSecurity('forgot_password_requested', 'Permintaan tautan reset password oleh pengguna', $user);
        }

        // Always return generic success message to prevent email enumeration (OWASP Top 10)
        return redirect()->back()->with('success', 'Tautan untuk mengatur ulang kata sandi telah dikirim ke email Anda jika terdaftar di sistem kami.');
    }

    /**
     * Show the reset form for the given token.
     */
    public function showResetForm(Request $request, string $token): View|RedirectResponse
    {
        $email = $request->query('email');

        if (! $email) {
            return redirect()->route('login')->with('error', 'Tautan reset kata sandi tidak valid.');
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (! $record || ! Hash::check($token, $record->token) || now()->subMinutes(60)->gt($record->created_at)) {
            return redirect()->route('login')->with('error', 'Tautan reset kata sandi tidak valid atau telah kedaluwarsa.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Reset the given user's password.
     */
    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->token, $record->token) || now()->subMinutes(60)->gt($record->created_at)) {
            return redirect()->route('login')->with('error', 'Tautan reset kata sandi tidak valid atau telah kedaluwarsa.');
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete token to invalidate link
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        SystemLogService::logSecurity('password_reset_success', 'Kata sandi berhasil diatur ulang oleh pengguna', $user);

        return redirect()->route('login')->with('success', 'Kata sandi Anda berhasil diperbarui. Silakan masuk menggunakan kata sandi baru Anda.');
    }
}
