<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
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
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // -----------------------------------------------------------------------
    //  Route Key
    // -----------------------------------------------------------------------

    /**
     * Use UUID as the route model binding key (prevents ID enumeration).
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // -----------------------------------------------------------------------
    //  Boot
    // -----------------------------------------------------------------------

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
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
        $role = $this->primaryRole();

        if ($role === 'siswa') {
            $userAgent = request()->header('User-Agent', '');
            $isMobile = (bool) preg_match('/(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini)/i', $userAgent);

            if ($isMobile) {
                return 'apps.home';
            }
        }

        return match ($role) {
            'super-admin', 'admin', 'guru', 'siswa' => 'dashboard',
            default => 'frontend.home',
        };
    }

    /**
     * Get the user's avatar URL or fall back to UI Avatars.
     */
    public function avatarUrl(): string
    {
        return $this->avatar
            ? asset('storage/'.$this->avatar)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=4f45b2&background=f0efff&bold=true';
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
    public function issueToken(string $name, array $abilities = ['*'], ?int $expiresInMinutes = null): NewAccessToken
    {
        $expiresAt = $expiresInMinutes ? now()->addMinutes($expiresInMinutes) : null;

        return $this->createToken($name, $abilities, $expiresAt);
    }
}
