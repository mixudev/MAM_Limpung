@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'User Accounts';
        }
    });
</script>

<div class="max-w-6xl space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">User Accounts</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Kelola data login pengguna, status keaktifan, dan atur penugasan role mereka.</p>
        </div>
        @can('create-users')
        <button type="button" onclick="openAddUserModal()" class="py-2 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-mono font-bold text-xs uppercase tracking-wider rounded-none transition-all text-center">
            Tambah User Baru
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-900 p-4 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm">
        <form action="" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                       class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-none">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            
            <div class="w-full md:w-48">
                <select name="role" onchange="this.form.submit()" 
                        class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none rounded-none">
                    <option value="">Semua Peran (Roles)</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->display_name ?: $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="py-2 px-5 bg-slate-800 hover:bg-slate-700 text-white font-mono font-bold text-xs uppercase tracking-wider rounded-none transition-colors">
                Filter
            </button>
            
            @if(request()->anyFilled(['search', 'role']))
                <a href="{{ request()->url() }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-600 dark:text-zinc-400 font-mono font-bold text-xs uppercase tracking-wider rounded-none transition-colors text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                        <th class="py-3 px-4">Nama / Email</th>
                        <th class="py-3 px-4">Peran (Roles)</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Login Terakhir</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse ($users as $u)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800 dark:text-zinc-200">{{ $u->name }}</div>
                                <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono mt-0.5">{{ $u->email }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($u->roles as $role)
                                        <span class="px-2 py-0.5 text-[9px] font-mono font-bold uppercase tracking-wider rounded-none 
                                                     bg-indigo-50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-400 border border-indigo-100/50 dark:border-indigo-900/30">
                                            {{ $role->display_name ?: $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($u->is_active)
                                    <span class="px-2 py-0.5 text-[9px] font-mono font-bold rounded-none bg-emerald-100 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-400">
                                        ACTIVE
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-[9px] font-mono font-bold rounded-none bg-rose-100 dark:bg-rose-950/20 text-rose-800 dark:text-rose-400">
                                        INACTIVE
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 dark:text-zinc-400">
                                @if ($u->last_login_at)
                                    <div class="font-semibold">{{ $u->last_login_at->diffForHumans() }}</div>
                                    <div class="text-[9px] font-mono text-slate-400 dark:text-zinc-500 mt-0.5">IP: {{ $u->last_login_ip }}</div>
                                @else
                                    <span class="text-slate-400 dark:text-zinc-600 font-mono">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    @can('view-users')
                                    <a href="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.users.show', $u) : route('admin.users.show', $u) }}" 
                                       class="py-1 px-2.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:hover:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 font-mono font-bold text-[10px] uppercase tracking-wider transition-colors border border-indigo-200 dark:border-indigo-800/50">
                                        Info
                                    </a>
                                    @endcan

                                    @can('delete-users')
                                    @if ($u->id === Auth::user()->id)
                                        <button type="button" disabled 
                                                class="py-1 px-2.5 bg-slate-100 dark:bg-zinc-800 text-slate-400 dark:text-zinc-600 font-mono font-bold text-[10px] uppercase tracking-wider cursor-not-allowed"
                                                title="Anda tidak bisa menghapus akun Anda sendiri">
                                            Hapus
                                        </button>
                                    @else
                                        <form id="delete-form-{{ $u->id }}" action="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.users.destroy', $u) : route('admin.users.destroy', $u) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmDeleteUser({{ $u->id }}, '{{ addslashes($u->name) }}')"
                                                    class="py-1 px-2.5 bg-rose-600 hover:bg-rose-700 text-white font-mono font-bold text-[10px] uppercase tracking-wider transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 dark:text-zinc-600 font-mono">
                                Tidak ada data user ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/30">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Partial -->
@include('dashboard.admin.security.users.partials.user_modal')

<script>
    function confirmDeleteUser(userId, userName) {
        if (window.AppPopup) {
            window.AppPopup.confirm({
                title: 'Hapus Akun User?',
                description: `Apakah Anda yakin ingin menghapus akun user '<b>${userName}</b>'? Tindakan ini akan men-soft delete akun tersebut.`,
                confirmText: 'Ya, Hapus',
                cancelText: 'Batal',
                onConfirm: () => {
                    const form = document.getElementById('delete-form-' + userId);
                    if (form) {
                        form.submit();
                    }
                }
            });
        } else {
            if (confirm(`Apakah Anda yakin ingin menghapus user '${userName}'?`)) {
                const form = document.getElementById('delete-form-' + userId);
                if (form) {
                    form.submit();
                }
            }
        }
    }
</script>
@endsection
