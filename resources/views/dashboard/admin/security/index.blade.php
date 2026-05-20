@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Role & Permissions';
        }
    });
</script>

<div class="max-w-6xl space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Role & Permissions</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Kelola tingkat hak akses pengguna, buat role baru, dan amankan pintu akses fitur sekolah.</p>
        </div>
        <button type="button" onclick="openAddRoleModal()" class="py-2 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-mono font-bold text-xs uppercase tracking-wider rounded-none transition-all text-center">
            Tambah Role Baru
        </button>
    </div>

    <!-- Tabs Container -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm overflow-hidden">
        
        <!-- Tab Headers -->
        <div class="flex border-b border-slate-200 dark:border-zinc-850 bg-slate-50 dark:bg-zinc-950">
            <button type="button" onclick="switchTab('tab-roles')" id="btn-tab-roles" 
                    class="tab-btn px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-indigo-600 text-indigo-600 dark:text-white transition-all focus:outline-none">
                Kelola Roles
            </button>
            <button type="button" onclick="switchTab('tab-permissions')" id="btn-tab-permissions" 
                    class="tab-btn px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 transition-all focus:outline-none">
                Daftar Permissions (Read-only)
            </button>
        </div>

        <div class="p-6">
            <!-- 1. TAB ROLES -->
            <div id="tab-roles" class="tab-content space-y-6">
                @include('dashboard.admin.security.partials.roles_tab')
            </div>

            <!-- 2. TAB PERMISSIONS -->
            <div id="tab-permissions" class="tab-content hidden space-y-6">
                @include('dashboard.admin.security.partials.permissions_tab')
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
@include('dashboard.admin.security.partials.role_modal')

<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-indigo-600', 'text-indigo-600', 'dark:text-white');
            btn.classList.add('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        });

        document.getElementById(tabId).classList.remove('hidden');
        
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        activeBtn.classList.add('border-indigo-600', 'text-indigo-600', 'dark:text-white');
    }
</script>
@endsection
