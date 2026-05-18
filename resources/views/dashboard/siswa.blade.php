<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><title>Siswa Dashboard</title>
<style>body{font-family:system-ui,sans-serif;background:#f3f4f6;padding:2rem}.header{background:#4c1d95;color:#fff;padding:1.25rem 1.5rem;border-radius:.5rem;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center}h1{font-size:1.1rem;font-weight:700}.card{background:#fff;padding:1.25rem;border-radius:.5rem;box-shadow:0 1px 3px rgba(0,0,0,.08);margin-bottom:1rem}.pill{display:inline-block;background:#f5f3ff;color:#4c1d95;padding:.2rem .6rem;border-radius:9999px;font-size:.75rem;margin:.15rem}.logout-btn{background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3);padding:.375rem 1rem;border-radius:.375rem;cursor:pointer;font-size:.875rem}</style>
</head><body>
<div class="header">
    <h1>🎓 Dashboard Siswa</h1>
    <div style="display:flex;align-items:center;gap:1rem">
        <span style="font-size:.85rem;opacity:.85">{{ $user->name }}</span>
        <form method="POST" action="{{ route('auth.logout') }}" style="margin:0">@csrf<button type="submit" class="logout-btn">Logout</button></form>
    </div>
</div>
<div class="card">
    <h2 style="font-size:.875rem;font-weight:600;margin-bottom:.75rem">Permissions Anda</h2>
    @foreach ($permissions as $p) <span class="pill">{{ $p }}</span> @endforeach
</div>
<div class="card"><p>Selamat datang, <strong>{{ $user->name }}</strong>. Anda login sebagai <strong>Siswa</strong>.</p></div>
</body></html>
