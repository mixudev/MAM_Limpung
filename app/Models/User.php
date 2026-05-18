<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // -----------------------------------------------------------------------
    //  Scopes
    // -----------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // -----------------------------------------------------------------------
    //  Helpers
    // -----------------------------------------------------------------------

    /**
     * Get the primary role name (highest-level role).
     */
    public function primaryRole(): ?string
    {
        return $this->roles->sortByDesc('level')->first()?->name;
    }

    /**
     * Determine the redirect path after login based on role.
     */
    public function dashboardRoute(): string
    {
        return match ($this->primaryRole()) {
            'super-admin' => 'super-admin.dashboard',
            'admin'       => 'admin.dashboard',
            'guru'        => 'guru.dashboard',
            'siswa'       => 'siswa.dashboard',
            default       => 'home',
        };
    }

    /**
     * Update last login metadata.
     */
    public function recordLogin(string $ip): void
    {
        $this->updateQuietly([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);
    }

    /**
     * Revoke all API tokens (useful for logout-everywhere).
     */
    public function revokeAllTokens(): void
    {
        $this->tokens()->delete();
    }

    /**
     * Revoke all tokens except the current one.
     */
    public function revokeOtherTokens(string $currentTokenId): void
    {
        $this->tokens()->where('id', '!=', $currentTokenId)->delete();
    }

    /**
     * Issue a scoped API token with optional expiry.
     */
    public function issueToken(string $name, array $abilities = ['*'], ?int $expiresInMinutes = null): \Laravel\Sanctum\NewAccessToken
    {
        $expiresAt = $expiresInMinutes ? now()->addMinutes($expiresInMinutes) : null;

        return $this->createToken($name, $abilities, $expiresAt);
    }
}
