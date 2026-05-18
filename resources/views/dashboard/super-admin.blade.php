<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Dashboard — {{ config('app.name') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f3f4f6; padding: 2rem; }
        .header { background: #1e40af; color: #fff; padding: 1.25rem 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        h1 { font-size: 1.1rem; font-weight: 700; }
        .badge { background: rgba(255,255,255,.2); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .card { background: #fff; padding: 1.25rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .card h2 { font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: .05em; }
        .pill { display: inline-block; background: #eff6ff; color: #1d4ed8; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; margin: 0.15rem; }
        .pill.permission { background: #f0fdf4; color: #166534; }
        .logout-btn { background: rgba(255,255,255,.15); color: #fff; border: 1px solid rgba(255,255,255,.3); padding: 0.375rem 1rem; border-radius: 0.375rem; cursor: pointer; font-size: 0.875rem; }
    </style>
</head>
<body>

<div class="header">
    <div>
        <h1>⚙️ Super Admin Dashboard</h1>
        <div style="font-size:.8rem;opacity:.8;margin-top:.25rem">{{ config('app.name') }}</div>
    </div>
    <div style="display:flex;align-items:center;gap:1rem">
        <span class="badge">{{ $user->name }}</span>
        <form method="POST" action="{{ route('auth.logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<div class="grid">
    {{-- User Info --}}
    <div class="card">
        <h2>Informasi Akun</h2>
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Login terakhir:</strong> {{ $user->last_login_at?->diffForHumans() ?? '-' }}</p>
        <p><strong>IP terakhir:</strong> {{ $user->last_login_ip ?? '-' }}</p>
    </div>

    {{-- Roles --}}
    <div class="card">
        <h2>Roles Assigned</h2>
        @forelse ($roles as $role)
            <span class="pill">{{ $role }}</span>
        @empty
            <span style="color:#9ca3af;font-size:.875rem">Tidak ada role.</span>
        @endforelse
    </div>

    {{-- Permissions --}}
    <div class="card">
        <h2>Permissions ({{ $permissions->count() }})</h2>
        <div style="max-height:200px;overflow-y:auto">
            @forelse ($permissions as $permission)
                <span class="pill permission">{{ $permission }}</span>
            @empty
                <span style="color:#9ca3af;font-size:.875rem">Tidak ada permission langsung.</span>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick checks --}}
<div class="card">
    <h2>Quick Permission Checks (Blade @@can)</h2>
    <table style="width:100%;border-collapse:collapse;font-size:.875rem">
        <thead>
            <tr style="background:#f9fafb">
                <th style="padding:.5rem;text-align:left;border-bottom:1px solid #e5e7eb">Permission</th>
                <th style="padding:.5rem;text-align:left;border-bottom:1px solid #e5e7eb">Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach (['view-users','create-users','delete-users','assign-roles','view-reports'] as $perm)
            <tr>
                <td style="padding:.5rem;border-bottom:1px solid #f3f4f6">{{ $perm }}</td>
                <td style="padding:.5rem;border-bottom:1px solid #f3f4f6">
                    @can($perm)
                        <span style="color:#16a34a">✓ Allowed</span>
                    @else
                        <span style="color:#dc2626">✗ Denied</span>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
