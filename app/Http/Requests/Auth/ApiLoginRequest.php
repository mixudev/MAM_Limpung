<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Throw if the IP+email key is over the limit.
     * Call this BEFORE attempting authentication.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
        ]);
    }

    /**
     * Record a failed attempt (hit the counter).
     * Call this AFTER authentication fails.
     */
    public function recordFailedAttempt(): void
    {
        RateLimiter::hit($this->throttleKey(), 900); // 15-minute decay
    }

    /**
     * Clear the rate limiter after a successful login.
     */
    public function clearRateLimit(): void
    {
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Throttle key: email + IP.
     */
    public function throttleKey(): string
    {
        return 'api-login|'.Str::transliterate(Str::lower($this->string('email'))).'|'.$this->ip();
    }
}
