# Unified Dashboard Implementation

## Overview

Dashboard sekarang menggunakan **single unified route** `/dashboard` yang accessible untuk semua roles (Super Admin, Admin, Guru, Siswa). Data yang ditampilkan dibatasi berdasarkan **permissions** yang dimiliki user menggunakan gates dan conditional logic.

## Perubahan Utama

### 1. **Satu Route Terpusat** (`routes/dashboard.php`)
```php
Route::get('/dashboard', UnifiedDashboardController::class)->name('dashboard');
```

**Sebelumnya**: 4 route terpisah
- `/super-admin/dashboard`
- `/admin/dashboard`
- `/guru/dashboard`
- `/siswa/dashboard`

**Sekarang**: 1 route unified
- `/dashboard`

### 2. **Unified Controller** (`app/Http/Controllers/Dashboard/UnifiedDashboardController.php`)

Controller menangani semua roles dengan mengecek permissions user:

```php
// Check permission untuk menentukan data apa yang ditampilkan
if ($user->can('access-super-admin-dashboard')) {
    // Load Super Admin stats & features
}

if ($user->can('access-admin-dashboard')) {
    // Load Admin stats & features
}

if ($user->can('access-guru-dashboard')) {
    // Load Guru stats & features
}

if ($user->can('access-siswa-dashboard')) {
    // Load Siswa stats & features
}
```

### 3. **Custom Middleware** (`app/Http/Middleware/CheckDashboardAccess.php`)

Middleware mengecek apakah user memiliki **minimal satu dashboard permission**:

```php
$dashboardPermissions = [
    'access-super-admin-dashboard',
    'access-admin-dashboard',
    'access-guru-dashboard',
    'access-siswa-dashboard',
];

if (! $request->user()->hasAnyPermission($dashboardPermissions)) {
    abort(403, 'Unauthorized access to dashboard');
}
```

Middleware didaftar di `bootstrap/app.php`:
```php
'check.dashboard.access' => CheckDashboardAccess::class,
```

## Keuntungan

✅ **Terpusat** - Satu route, satu controller, mudah dipelihara  
✅ **Flexible** - Data ditampilkan berdasarkan permissions  
✅ **Scalable** - Mudah tambah role baru  
✅ **Security** - Middleware layer tambahan untuk verification  
✅ **Mudah Testing** - Satu endpoint untuk ditest semua roles  

## Navigasi & URL Update

### Old Navigation
```php
route('super-admin.dashboard')  // /super-admin/dashboard
route('admin.dashboard')         // /admin/dashboard
route('guru.dashboard')          // /guru/dashboard
route('siswa.dashboard')         // /siswa/dashboard
```

### New Navigation
```php
route('dashboard')  // /dashboard (untuk semua roles)
```

**Jika masih ada link yang mereferensi route lama**, update ke route baru:
```php
// Template atau navigation files
// Ubah: route('admin.dashboard') => route('dashboard')
// Ubah: route('guru.dashboard') => route('dashboard')
// dst...
```

## Modular Routes (Tetap Ada)

Fitur-fitur spesifik per role tetap modular di folder `routes/dashboard/`:
- `ppdb.php` → `/admin/ppdb/*` (Admin PPDB management)
- `articles.php` → `/admin/articles/*` (Admin article management)
- `profile.php` → `/admin/profile` (User profile)
- `users.php` → `/admin/users/*` (User management)
- dll...

**Prefix tetap menggunakan role-based naming** karena feature-feature tersebut spesifik per role.

## Gates Implementation

Untuk menampilkan widget/section tertentu di dashboard view, gunakan gates:

```blade
@can('access-super-admin-dashboard')
    <div class="super-admin-stats">
        <!-- Super Admin specific content -->
    </div>
@endcan

@can('access-admin-dashboard')
    <div class="admin-stats">
        <!-- Admin specific content -->
    </div>
@endcan

@can('access-guru-dashboard')
    <div class="guru-stats">
        <!-- Guru specific content -->
    </div>
@endcan

@can('access-siswa-dashboard')
    <div class="siswa-stats">
        <!-- Siswa specific content -->
    </div>
@endcan
```

## Testing

Pastikan test semua roles:

```bash
# Test akses dashboard
php artisan test --filter=dashboard

# Test dengan role spesifik
php artisan tinker
$user = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
auth()->login($user);
```

## Konfigurasi Permissions

Pastikan semua permission sudah ada di database:
- `access-super-admin-dashboard`
- `access-admin-dashboard`
- `access-guru-dashboard`
- `access-siswa-dashboard`

Jalankan seeder jika permissions belum ada:
```bash
php artisan db:seed --class=PermissionSeeder
```

---

**Created**: June 1, 2026  
**Last Updated**: June 1, 2026
