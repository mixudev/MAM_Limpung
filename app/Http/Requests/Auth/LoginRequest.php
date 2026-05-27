<?php

namespace App\Http\Requests\Auth;

use App\Services\SystemLogService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');

        // SECURITY: Never use remember-me — session expires on browser close.
        if (! Auth::attempt($credentials, false)) {
            // Hit BOTH throttle keys on every failed attempt.
            RateLimiter::hit($this->throttleKey(), 300);          // per IP+email, 5-min decay
            RateLimiter::hit($this->globalThrottleKey(), 600);    // per email only, 10-min decay

            SystemLogService::logSecurity('login_failed', 'Gagal masuk: email atau password salah untuk '.$this->input('email'));

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Ensure account is active
        if (! Auth::user()->is_active) {
            $inactiveUser = Auth::user();
            Auth::logout();

            SystemLogService::logSecurity('login_failed_inactive', 'Gagal masuk: akun tidak aktif untuk '.$inactiveUser->email, $inactiveUser);

            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ]);
        }

        // Clear both throttle counters on successful login.
        RateLimiter::clear($this->throttleKey());
        RateLimiter::clear($this->globalThrottleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * Two layers:
     *  1. Per IP+email   — blocks single-source brute-force (max 5 per 5 min)
     *  2. Per email only — blocks distributed botnet attacks  (max 15 per 10 min)
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        // Layer 1: per IP + email
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            event(new Lockout($this));

            $seconds = RateLimiter::availableIn($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // Layer 2: global per email (botnet protection)
        if (RateLimiter::tooManyAttempts($this->globalThrottleKey(), 15)) {
            $seconds = RateLimiter::availableIn($this->globalThrottleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }
    }

    /**
     * Rate limiting throttle key: email + IP address.
     * Blocks single-source attacks.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * Global throttle key: email only.
     * Blocks distributed / botnet attacks against a single account.
     */
    public function globalThrottleKey(): string
    {
        return 'global|'.Str::transliterate(Str::lower($this->string('email')));
    }
}
